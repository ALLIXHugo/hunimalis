<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\CategorieProduit;
use App\Models\Professionnel;
use App\Models\Facture;
use App\Models\Commande;
use App\Models\Client;
use App\Models\Personne;
use App\Models\Espece;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Auth;
use App\Models\CodePromo; 
use Illuminate\Http\Request;

use Stripe\Stripe;
use Stripe\Charge;

class ProduitController extends Controller
{

    public function index(){
        $user = Auth::user();
        $pro = Professionnel::where('idpersonne', $user->idpersonne)->first();
        
        $produits = Produit::where('idpro', $pro->idpro)->orderBy('nomproduit')->get();
        return view("produit.produit", compact('produits'));
    }

    public function create() {
        $categories = CategorieProduit::orderBy('libellecategoriepro')->get();
        $user = Auth::user();
        $pro = Professionnel::where('idpersonne', $user->idpersonne)->firstOrFail();
        $tauxTva = $pro->tva ?? 0; 
        return view('produit.create', compact('categories', 'tauxTva'));
    }

    public function store(Request $request) {
        $user = Auth::user();
        $pro = Professionnel::where('idpersonne', $user->idpersonne)->firstOrFail();

        $request->validate([
            'nomproduit' => 'required|string|max:100',
            'libellecategoriepro' => 'required',
            'prixachat' => 'required|numeric|min:0',
            'prixvente' => 'required|numeric|min:0',
            'stocks' => 'required|integer|min:0',
            'seuilalerte' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048', 
            'idespece' => 'nullable|exists:espece,idespece', 
        ]);

        $cheminImage = null;
        if ($request->hasFile('image')) {
            $cheminImage = $request->file('image')->store('produits', 'public');
        }

        $nomFinal = $request->nomproduit;
        
        if ($request->filled('idespece')) {
            $espece = Espece::find($request->idespece);
            if ($espece) {
                $nomFinal .= " (" . ucfirst($espece->libelleespece) . ")";
            }
        }

        Produit::create([
            'idpro'               => $pro->idpro,
            'libellecategoriepro' => $request->libellecategoriepro,
            'nomproduit'          => $nomFinal,
            'prixachat'           => $request->prixachat,
            'prixvente'           => $request->prixvente,
            'numserie'            => $request->numserie,
            'numlot'              => $request->numlot,
            'stocks'              => $request->stocks,
            'seuilalerte'         => $request->seuilalerte,
            'photo'               => $cheminImage, 
        ]);

        return back()->with('success', 'Produit ajouté avec succès.');
    }

    public function edit($id) {
        $produit = Produit::findOrFail($id);
        $user = Auth::user();
        $pro = Professionnel::where('idpersonne', $user->idpersonne)->first();
        if($produit->idpro !== $pro->idpro) abort(403);

        $categories = CategorieProduit::orderBy('libellecategoriepro')->get();
        $tauxTva = $pro->tva ?? 0;
        return view('produit.edit', compact('produit', 'categories', 'tauxTva'));
    }

    public function update(Request $request, $id) {
        $produit = Produit::findOrFail($id);
        
        $user = Auth::user();
        $pro = Professionnel::where('idpersonne', $user->idpersonne)->first();
        if($produit->idpro !== $pro->idpro) abort(403);

        $data = $request->except(['image']);
        if ($request->hasFile('image')) {            
            $data['photo'] = $request->file('image')->store('produits', 'public');
        }
        $produit->update($data);
        return redirect()->route('produit.index')->with('success', 'Produit mis à jour.');
    }

    public function show($id) {
        $produit = Produit::findOrFail($id);
        return view('produit.show', compact('produit'));
    }

    public function boutique(Request $request) {
        $produits = Produit::with(['categorieproduit', 'professionnel.personne'])
            ->orderBy('nomproduit')
            ->get(); 
        $categories = CategorieProduit::orderBy('libellecategoriepro')->get();
        
        return view('produit.produitClient', compact('produits', 'categories'));
    }

    public function detailProduit($id) {
        $produit = Produit::with(['categorieproduit', 'professionnel.personne'])->findOrFail($id);
        return view('produit.produitDetailClient', compact('produit'));
    }


    public function panierPro()
    {
        $panier = session()->get('panier_pro', []);
        $total = 0;
        foreach($panier as $item) { $total += $item['prix'] * $item['quantite']; }

        $clientsData = Client::with('personne')->get()->map(function($client) {
                return [
                    'id' => $client->idclient,
                    'label' => strtoupper($client->personne->nom) . ' ' . ucfirst($client->personne->prenom)
                ];
            })->values(); 

        return view('produit.panierPro', compact('panier', 'total', 'clientsData'));
    }

    public function ajouterAuPanierPro(Request $request, $id) {
        $produit = Produit::findOrFail($id);
        $quantite = (int) $request->input('quantity', 1);

        if($quantite > $produit->stocks) return back()->with('error', "Stock insuffisant.");

        $panier = session()->get('panier_pro', []);
        if(isset($panier[$id])) {
            $panier[$id]['quantite'] += $quantite;
        } else {
            $panier[$id] = [
                'id' => $produit->idproduit, 'nom' => $produit->nomproduit,
                'photo' => $produit->photo, 'prix' => $produit->prixvente, 
                'quantite' => $quantite, 'stock_max' => $produit->stocks,
            ];
        }
        session()->put('panier_pro', $panier);
        return back()->with('success', "Ajouté au panier !");
    }
    
    public function supprimerDuPanierPro($id) {
        $panier = session()->get('panier_pro', []);
        if(isset($panier[$id])) {
            unset($panier[$id]);
            session()->put('panier_pro', $panier);
        }
        return back()->with('success', "Retiré.");
    }

    public function viderPanierPro() {
        session()->forget('panier_pro');
        return back()->with('success', "Panier vidé.");
    }
    
    public function updatePanierPro(Request $request, $id) {
        $produit = Produit::findOrFail($id);
        $newQty = (int) $request->quantity;
        if($newQty > $produit->stocks) return back()->with('error', "Stock max atteint.");

        $panier = session()->get('panier_pro', []);
        if(isset($panier[$id])) {
            $panier[$id]['quantite'] = $newQty;
            session()->put('panier_pro', $panier);
            return back()->with('success', "Quantité mise à jour.");
        }
        return back()->with('error', "Erreur produit.");
    }
    
    public function validerCommandePro(Request $request) {
         $panier = session()->get('panier_pro', []);
         if(empty($panier)) return back()->with('error', "Panier vide.");
         
         DB::beginTransaction();
         try {
             $user = Auth::user();
             $idClient = $request->input('idclient');
             $clientData = DB::table('client')->where('idclient', $idClient)->first();
             $idPersonneClient = $clientData ? $clientData->idpersonne : null;

             $idFacture = DB::table('facture')->insertGetId([
                'idpersonne' => $idPersonneClient,
                'idclient' => $idClient,
                'idcommande' => null
             ], 'idfacture');
             
             $idCommande = DB::table('commande')->insertGetId([
                'idfacture' => $idFacture, 'datecommande' => now()
             ], 'idcommande');
             
             DB::table('facture')->where('idfacture', $idFacture)->update(['idcommande' => $idCommande]);

             foreach ($panier as $id => $item) {
                 DB::table('quantite')->insert(['idproduit'=>$id, 'idcommande'=>$idCommande, 'quantite'=>$item['quantite']]);
                 DB::table('produit')->where('idproduit', $id)->decrement('stocks', $item['quantite']);
             }
             DB::commit();
             session()->forget('panier_pro');
             return redirect()->route('produit.index')->with('success', "Vente validée.");
         } catch (\Exception $e) {
             DB::rollBack();
             return back()->with('error', $e->getMessage());
         }
    }
    

    public function panierClient() {
        $panier = session()->get('panier_client', []);
        $total = 0;
        foreach($panier as $item) { $total += $item['prix'] * $item['quantite']; }
        return view('produit.panierClient', compact('panier', 'total'));
    }

    public function addToOrder(Request $request, $id) {
        if (!Auth::check()) return redirect()->route('login');
        $produit = Produit::findOrFail($id);
        $quantite = (int) $request->input('quantity', 1);

        if ($quantite > $produit->stocks) return back()->with('error', "Stock insuffisant.");

        $panier = session()->get('panier_client', []);
        if (isset($panier[$id])) {
            $panier[$id]['quantite'] += $quantite;
        } else {
            $panier[$id] = [
                'id' => $produit->idproduit, 'nom' => $produit->nomproduit,
                'photo' => $produit->photo, 'prix' => $produit->prixvente,
                'quantite' => $quantite, 'stock_max' => $produit->stocks,
            ];
        }
        session()->put('panier_client', $panier);
        return redirect()->route('client.boutique')->with('success', "Ajouté au panier !");
    }

    public function updatePanierClient(Request $request, $id) {
        $produit = Produit::findOrFail($id);
        $newQty = (int) $request->quantity;
        if($newQty > $produit->stocks) return back()->with('error', "Stock max atteint.");

        $panier = session()->get('panier_client', []);
        if(isset($panier[$id])) {
            $panier[$id]['quantite'] = $newQty;
            session()->put('panier_client', $panier);
            return back()->with('success', "Mis à jour.");
        }
        return back()->with('error', "Erreur.");
    }

    public function supprimerDuPanierClient($id) {
        $panier = session()->get('panier_client', []);
        if(isset($panier[$id])) {
            unset($panier[$id]);
            session()->put('panier_client', $panier);
        }
        return back()->with('success', "Retiré.");
    }

    public function viderPanierClient() {
        session()->forget('panier_client');
        return back()->with('success', "Panier vidé.");
    }

    public function checkout() {
        if (!Auth::check()) return redirect()->route('login');
        
        $panier = session()->get('panier_client', []);
        if (empty($panier)) return redirect()->route('client.boutique');

        $user = Auth::user();
        $client = Client::where('idpersonne', $user->idpersonne)->first();

        $totalBrut = 0;
        foreach($panier as $item) { 
            $totalBrut += $item['prix'] * $item['quantite']; 
        }

        $remisePromo = 0;
        $sessionPromo = session()->get('code_promo');
        
        if ($sessionPromo) {
            if ($sessionPromo['type'] == 'pourcentage') {
                $remisePromo = $totalBrut * ($sessionPromo['valeur'] / 100);
            } else {
                $remisePromo = $sessionPromo['valeur'];
            }
        }
        if ($remisePromo > $totalBrut) $remisePromo = $totalBrut;
        $totalApresPromo = $totalBrut - $remisePromo;

        $solde_avoir = $client->solde_avoir ?? 0;
        $avoir_utilise = 0;
        $reste_a_payer = $totalApresPromo;

        if ($solde_avoir > 0) {
            if ($solde_avoir >= $totalApresPromo) {
                $avoir_utilise = $totalApresPromo;
                $reste_a_payer = 0;
            } else {
                $avoir_utilise = $solde_avoir;
                $reste_a_payer = $totalApresPromo - $solde_avoir;
            }
        }

        $cartes = collect();
        if ($reste_a_payer > 0 && $client && $client->stripe_id) {
            try {
                Stripe::setApiKey(env('STRIPE_SECRET'));
                $paymentMethods = Customer::allSources($client->stripe_id, ['object' => 'card', 'limit' => 10]);
                $cartes = collect($paymentMethods->data);
            } catch (\Exception $e) {}
        }

        return view('clients.paiement', compact(
            'panier', 'totalBrut', 'remisePromo', 'totalApresPromo', 
            'cartes', 'solde_avoir', 'avoir_utilise', 'reste_a_payer', 'sessionPromo'
        ));
    }

    public function processPaiement(Request $request) {
        if (!Auth::check()) return redirect()->route('login');

        $user = Auth::user();
        $client = Client::where('idpersonne', $user->idpersonne)->first();
        $panier = session()->get('panier_client', []);

        if (!$client || empty($panier)) return redirect()->route('client.boutique');

        
        $totalBrut = 0;
        foreach($panier as $item) { $totalBrut += $item['prix'] * $item['quantite']; }

        $remisePromo = 0;
        $sessionPromo = session()->get('code_promo');
        if ($sessionPromo) {
             $checkCode = CodePromo::where('code', $sessionPromo['code'])->first();
             if($checkCode) {
                if ($sessionPromo['type'] == 'pourcentage') {
                    $remisePromo = $totalBrut * ($sessionPromo['valeur'] / 100);
                } else {
                    $remisePromo = $sessionPromo['valeur'];
                }
             }
        }
        if ($remisePromo > $totalBrut) $remisePromo = $totalBrut;
        $totalApresPromo = $totalBrut - $remisePromo;

        $solde_avoir = $client->solde_avoir ?? 0;
        $reste_a_payer = $totalApresPromo;
        $nouveau_solde_client = $solde_avoir;

        if ($solde_avoir > 0) {
            if ($solde_avoir >= $totalApresPromo) {
                $reste_a_payer = 0;
                $nouveau_solde_client = $solde_avoir - $totalApresPromo;
            } else {
                $reste_a_payer = $totalApresPromo - $solde_avoir;
                $nouveau_solde_client = 0;
            }
        }

        if ($reste_a_payer > 0) {
            $request->validate([
                'stripeToken' => 'required_if:payment_method,new_card',
                'card_id' => 'required_if:payment_method,saved_card',
            ]);
        }

        DB::beginTransaction();
        try {
            if ($reste_a_payer > 0) {
                Stripe::setApiKey(env('STRIPE_SECRET'));

                if (!$client->stripe_id) {
                    $customer = \Stripe\Customer::create([
                        'email' => $user->email,
                        'name' => $user->personne->prenom . ' ' . $user->personne->nom,
                        'metadata' => ['idclient' => $client->idclient]
                    ]);
                    $client->stripe_id = $customer->id;
                    $client->save();
                }

                $sourceId = null;
                $shouldDeleteCard = false;

                if ($request->payment_method === 'saved_card' && $request->card_id) {
                    $sourceId = $request->card_id;
                } elseif ($request->stripeToken) {
                    $card = \Stripe\Customer::createSource($client->stripe_id, ['source' => $request->stripeToken]);
                    $sourceId = $card->id;
                    if (!$request->has('save_card')) {
                        $shouldDeleteCard = true;
                    }
                } else {
                    throw new \Exception("Moyen de paiement invalide.");
                }

                Charge::create([
                    'amount' => round($reste_a_payer * 100),
                    'currency' => 'eur',
                    'customer' => $client->stripe_id,
                    'source' => $sourceId,
                    'description' => 'Commande Web',
                ]);

                if ($shouldDeleteCard) {
                    \Stripe\Customer::deleteSource($client->stripe_id, $sourceId);
                }
            }

            if ($solde_avoir != $nouveau_solde_client) {
                $client->solde_avoir = $nouveau_solde_client;
                $client->save();
            }

            $idFacture = DB::table('facture')->insertGetId([
                'idpersonne' => $user->idpersonne, 
                'idclient' => $client->idclient, 
                'idcommande' => null,
                'total' => $totalApresPromo, 
                'statut' => 'payee', 
                'date_facture' => now()
            ], 'idfacture');

            $idCommande = DB::table('commande')->insertGetId([
                'idfacture' => $idFacture, 
                'datecommande' => now()
            ], 'idcommande');

            DB::table('facture')->where('idfacture', $idFacture)->update(['idcommande' => $idCommande]);

            foreach ($panier as $id => $item) {
                $produit = DB::table('produit')->where('idproduit', $id)->lockForUpdate()->first();
                if (!$produit || $produit->stocks < $item['quantite']) {
                    throw new \Exception("Stock insuffisant : " . $item['nom']);
                }
                DB::table('quantite')->insert(['idproduit' => $id, 'idcommande' => $idCommande, 'quantite' => $item['quantite']]);
                DB::table('produit')->where('idproduit', $id)->decrement('stocks', $item['quantite']);
            }

            DB::commit();
            
            session()->forget('panier_client');
            session()->forget('code_promo'); 

            return redirect()->route('client.boutique')->with('success', "Commande validée !");

        } catch (CardException $e) {
            DB::rollBack();
            return back()->with('error', "Paiement refusé : " . $e->getError()->message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', "Erreur : " . $e->getMessage());
        }
    }


    public function gestionStock(Request $request)
    {
        $search = $request->input('search');

        $produits = Produit::query()
            ->when($search, function ($query, $search) {
                return $query->where('nomproduit', 'ILIKE', "%{$search}%")
                             ->orWhere('numserie', 'LIKE', "%{$search}%")
                             ->orWhere('numlot', 'LIKE', "%{$search}%");
            })
            ->orderBy('nomproduit')
            ->get();

        return view('produit.gestion_stock', compact('produits', 'search'));
    }

    public function updateStockRapide(Request $request, $id)
    {
        $request->validate([
            'nouveau_stock' => 'required|integer|min:0'
        ]);

        $produit = Produit::findOrFail($id);
        $ancienStock = $produit->stocks;

        $produit->stocks = $request->nouveau_stock;
        $produit->save();

        return back()->with('success', "Stock de '{$produit->nomproduit}' mis à jour : {$ancienStock} ➝ {$produit->stocks}");
    }

    public function updateStock(Request $request, $id)
    {
        $request->validate([
            'nouveau_stock' => 'required|integer|min:0'
        ]);
    
        $produit = Produit::where('idproduit', $id)
            ->where('idpro', Auth::user()->professionnel->idpro) 
            ->firstOrFail();
    
        $produit->stocks = $request->nouveau_stock;
        $produit->save();
    
        return back()->with('success', 'Stock mis à jour : ' . $produit->nomproduit . ' (' . $produit->stocks . ')');
    }



    public function appliquerCodePromo(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        
        $promo = CodePromo::where('code', strtoupper($request->code))
                    ->where(function($query) {
                        $query->whereNull('date_fin')
                              ->orWhere('date_fin', '>=', now());
                    })
                    ->first();

        if (!$promo) {
            return back()->with('error', 'Code promo invalide ou expiré.');
        }

        session()->put('code_promo', [
            'code' => $promo->code,
            'type' => $promo->type, 
            'valeur' => $promo->valeur
        ]);

        return back()->with('success', 'Code promo appliqué !');
    }

    public function retirerCodePromo()
    {
        session()->forget('code_promo');
        return back()->with('success', 'Code promo retiré.');
    }
}