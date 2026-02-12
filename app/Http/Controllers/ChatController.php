<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB; 

use App\Models\Produit; 
use App\Models\Client;
use App\Models\Animal;
use App\Models\Professionnel;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        $userMessage = $request->input('message');
        $history = $request->input('history', []); 
        $apiKey = config('services.gemini.key');
        $siteUrl = url('/'); 

        $userName = 'Visiteur';
        $contexteAnimaux = "Aucun (Utilisateur non connecté)";

        if (Auth::check()) {
            $user = Auth::user();
            if ($user->personne) {
                $userName = $user->personne->prenom;
                $client = Client::where('idpersonne', $user->idpersonne)->first();
                
                if ($client) {
                    $animaux = Animal::where('idclient', $client->idclient)->get();
                    if ($animaux->count() > 0) {
                        $contexteAnimaux = $animaux->map(function($a) {
                            return "- {$a->nom1animal} (Espece ID: {$a->idespece})";
                        })->implode("\n");
                    }
                }
            }
        }

        if (!$apiKey) {
            return response()->json(['reply' => "Maintenance : Clé API manquante."]);
        }

        $conversationHistory = "";
        if (!empty($history)) {
            foreach ($history as $msg) {
                $cleanText = strip_tags($msg['text']); 
                $role = ($msg['sender'] === 'user') ? "Utilisateur" : "Assistant";
                $conversationHistory .= "$role : $cleanText\n";
            }
        }

        $context = "Tu es l'assistant de Hunimalis. Utilisateur: $userName.
        
        CONTEXTE ANIMAUX CLIENT :
        $contexteAnimaux

        RÈGLES DE FORMATAGE (HTML STRICT) :
        - JAMAIS de Markdown. Pas de **gras**, pas de [lien](url).
        - Utilise uniquement <br>, <b>, <i>, <a>.
        - Pas de balises blocs (<p>, <div>, <ul>).

        OUTILS DISPONIBLES (COMMANDES CACHÉES) :

        1. RECHERCHER UN PROFESSIONNEL / PRESTATION
           Si l'utilisateur cherche un service, un métier ou un lieu (ex: 'Cherche un véto', 'Toiletteur à Lyon', 'Qui peut garder mon chien ?').
           -> Réponds UNIQUEMENT par : [[SEARCH_PRO:terme_metier:ville]]
           (Laisse la ville vide si non précisée. Ex: [[SEARCH_PRO:vétérinaire:]])

        2. AJOUTER AU PANIER (PRODUIT)
           Si l'utilisateur veut acheter un produit physique.
           -> Réponds UNIQUEMENT par : [[ADD:quantité:nom_produit]]

        3. CONVERSATION NORMALE
           Si l'utilisateur discute ou demande de l'aide générale.
           -> Réponds poliment en HTML.

        HISTORIQUE :
        $conversationHistory
        
        MESSAGE ACTUEL :
        $userMessage";

        try {
            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
                'contents' => [['parts' => [['text' => $context]]]]
            ]);
        } catch (\Exception $e) {
            Log::error("Gemini Error: " . $e->getMessage());
            return response()->json(['reply' => "Erreur de connexion."]);
        }

        if ($response->failed()) {
            return response()->json(['reply' => "Oups, petit souci technique."]);
        }

        $data = $response->json();
        $botReply = $data['candidates'][0]['content']['parts'][0]['text'] ?? "Je n'ai pas compris.";
        
        $botReply = str_replace(['```html', '```', '<p>', '</p>'], ['', '', '', '<br>'], $botReply);

        if (preg_match('/\[\[ADD:(\d+):(.*?)\]\]/', $botReply, $matches)) {
            $qty = (int)$matches[1];
            $searchTerm = trim($matches[2]);
            $actionResult = $this->addToCartLogic($qty, $searchTerm);
            $botReply = str_replace($matches[0], $actionResult, $botReply);
        }

        if (preg_match('/\[\[SEARCH_PRO:(.*?):(என.*?)?\]\]/', $botReply . '::', $matches)) {
            $cleanTag = str_replace(['[[SEARCH_PRO:', ']]'], '', $matches[0]);
            $parts = explode(':', $cleanTag);
            $term = isset($parts[0]) ? trim($parts[0]) : '';
            $ville = isset($parts[1]) ? trim($parts[1]) : '';

            $searchResult = $this->searchProLogic($term, $ville);
            $botReply = str_replace($matches[0], $searchResult, $botReply);
        }
        
        if (preg_match('/\[\[SEARCH_PRO:(.*?)\]\]/', $botReply, $matches)) {
             $term = trim($matches[1]);
             if (strpos($term, ':') !== false) {
                 list($t, $v) = explode(':', $term);
                 $searchResult = $this->searchProLogic($t, $v);
             } else {
                 $searchResult = $this->searchProLogic($term, '');
             }
             $botReply = str_replace($matches[0], $searchResult, $botReply);
        }

        return response()->json(['reply' => $botReply]);
    }

    private function searchProLogic($term, $ville)
    {
        $query = Professionnel::query();

        if (!empty($term)) {
            $query->where(function($q) use ($term) {
                $q->where('libelleetablissement', 'ILIKE', '%' . $term . '%')
                  ->orWhere('libelletypeetablissement', 'ILIKE', '%' . $term . '%')
                  ->orWhereHas('prestations', function($subQuery) use ($term) {
                      $subQuery->where('nomprestation', 'ILIKE', '%' . $term . '%');
                  });
            });
        }

        if (!empty($ville)) {
            $query->where(function($q) use ($ville) {
                $q->where('ville', 'ILIKE', '%' . $ville . '%')
                  ->orWhere('cp', 'LIKE', $ville . '%');
            });
        }

        $results = $query->limit(3)->get(); 

        if ($results->isEmpty()) {
            return "<br><i>🕵️ Désolé, je n'ai trouvé aucun professionnel correspondant à '$term' " . ($ville ? "à $ville" : "") . ".</i>";
        }

        $html = "<br><b> Voici ce que j'ai trouvé :</b><br>";
        
        foreach ($results as $pro) {
            $url = route('client.pro.details', ['id' => $pro->idpro]);
            
            $html .= "<div style='border: 1px solid #e5e7eb; padding: 10px; margin-top: 5px; border-radius: 8px; background-color: #f9fafb;'>
                        <b style='color:#111827;'>{$pro->libelleetablissement}</b><br>
                        <span style='font-size:0.9em; color:#4b5563;'>📍 {$pro->ville} ({$pro->cp})</span><br>
                        <span style='font-size:0.9em; color:#6b7280;'>🛠️ {$pro->libelletypeetablissement}</span><br>
                        <a href='{$url}' style='color: #2563eb; font-weight: bold; text-decoration: underline; font-size: 0.9em;'>📅 Voir disponibilités & Réserver</a>
                      </div>";
        }

        return $html . "<br>";
    }


    private function addToCartLogic($quantity, $searchTerm)
    {
        if (!Auth::check()) {
            $loginUrl = route('login');
            return "<br><div style='background-color: #fff3cd; color: #856404; padding: 10px; border: 1px solid #ffeeba;'>
                        🔒 <b>Connexion requise</b><br>
                        Connectez-vous pour ajouter au panier.<br>
                        <a href='{$loginUrl}' style='font-weight:bold; text-decoration:underline;'>Se connecter</a>
                    </div>";
        }

        $produits = Produit::all();
        $product = $produits->filter(function($item) use ($searchTerm) {
            return false !== stripos($item->nomproduit, $searchTerm) || 
                   false !== stripos($item->description, $searchTerm);
        })->first();

        if (!$product) {
            return "<br><i>⚠️ Produit '$searchTerm' introuvable.</i>";
        }

        if ($product->stocks < $quantity) {
            return "<br><i>⚠️ Stock insuffisant ({$product->stocks} restants).</i>";
        }

        $panier = Session::get('panier_client', []);
        $id = $product->idproduit;

        $qteActuelle = isset($panier[$id]) ? $panier[$id]['quantite'] : 0;
        
        if (($qteActuelle + $quantity) > $product->stocks) {
            return "<br><i>⚠️ Stock max atteint pour ce produit.</i>";
        }

        if (isset($panier[$id])) {
            $panier[$id]['quantite'] += $quantity;
        } else {
            $panier[$id] = [
                'id' => $product->idproduit,
                'nom' => $product->nomproduit,
                'photo' => $product->photo,
                'prix' => $product->prixvente,
                'quantite' => $quantity,
                'stock_max' => $product->stocks,
            ];
        }

        Session::put('panier_client', $panier);
        Session::save();

        $s = ($quantity > 1) ? 's' : '';
        return "<br><div style='background-color: #d1fae5; color: #065f46; padding: 10px; border-radius: 5px; margin-top: 5px;'>
                    ✅ <b>{$quantity} {$product->nomproduit}</b> ajouté{$s} au panier !<br>
                    <a href='".url('/panier')."' style='text-decoration:underline;'>Voir mon panier</a>
                </div>";
    }
}