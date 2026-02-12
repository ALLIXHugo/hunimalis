<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Personne;
use App\Models\Animal;
use App\Models\Client;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;     

use Stripe\Stripe;
use Stripe\Customer;


class ClientController extends Controller
{
    public function create()
    {
        $clients = Client::all();
        return view('clients.creerclient', ['clients' => $clients]);
    }  

    
    public function store(Request $request)
    {
        $request->validate([
            'nom'    => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'ville'  => 'required|string|max:100',
            'cp'     => 'required|string|max:5', 
            'tel'    => ['nullable', 'string', 'max:10', 'regex:/^(06|07|03|04|01|02|05|09)[0-9]{8}$/', 'unique:personne,tel'],
            'mail'   => ['nullable', 'email', 'max:100', 'unique:personne,mail'],
        ]);

        if (empty($request->tel) && empty($request->mail)) {
            return back()->withInput()->withErrors(['contact' => 'Veuillez renseigner au moins un téléphone ou un email.']);
        }

        DB::beginTransaction();

        try {
            $personne = Personne::create([
                'nom'    => $request->nom,
                'prenom' => $request->prenom,
                'tel'    => $request->tel,
                'mail'   => $request->mail,
            ]);

            Client::create([
                'idpersonne'  => $personne->idpersonne,
                'ville'       => $request->ville, 
                'code_postal' => $request->cp,    
            ]);

            DB::commit();

            return redirect()->route('client.create')
                ->with('success', "Le client {$request->prenom} {$request->nom} a été créé avec succès.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erreur système : ' . $e->getMessage());
        }
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $personne = Personne::findOrFail($user->idpersonne);

        $request->validate([
            'prenom'   => 'required|string|max:100',
            'nom'      => 'required|string|max:100',
            'tel'      => ['nullable', 'string', 'max:10', 'regex:/^(06|07|03|04|01|02|05|09)[0-9]{8}$/'],
            'mail'     => ['nullable', 'email', 'max:100', 'regex:/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/'],
            'civilite' => 'nullable|string',
            'ville'    => 'nullable|string|max:100', 
            'cp'       => 'nullable|string|max:5',   
            'avatar'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', 
        ]);

        $personne->prenom   = $request->prenom;
        $personne->nom      = $request->nom;
        $personne->tel      = $request->tel;
        $personne->mail     = $request->mail;
        $personne->civilite = $request->civilite;
        $personne->note     = $request->input('note');

        if ($request->hasFile('avatar')) {
            if ($personne->avatar && Storage::disk('public')->exists($personne->avatar)) {
                Storage::disk('public')->delete($personne->avatar);
            }
            $path = $request->file('avatar')->store('pp', 'public');
            $personne->avatar = $path;
        }

        try {
            $personne->save(); 

            $client = Client::where('idpersonne', $user->idpersonne)->first();
            
            if ($client) {
                $client->ville = $request->ville;
                $client->code_postal = $request->cp;
                $client->save();
            }
            
            return back()->with('success', 'Votre profil a été mis à jour avec succès.');

        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['sauvegarde' => 'Erreur : ' . $e->getMessage()]);
        }
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'], 
        ]);

        $user = Auth::user(); 
        $personne = Personne::find($user->idpersonne);
        $client = Client::where('idpersonne', $user->idpersonne)->first();

        if ($client) {
            $hasActiveRdv = Rdv::where('idclient', $client->idclient)
                                        ->whereIn('idstatut', [1, 2]) 
                                        ->exists();

            if ($hasActiveRdv) {
                return back()->with('error', 'Action impossible : Vous avez des rendez-vous à venir ou en cours. Veuillez les annuler ou attendre leur fin avant de supprimer votre compte.');
            }
        }

        DB::beginTransaction();

        try {
            $personne->nom = 'ANONYME'; 
            $personne->prenom = 'Utilisateur ' . $user->idutilisateur; 
            $personne->tel = null;
            $personne->mail = 'deleted_' . $user->idutilisateur . '_' . time() . '@hunimalis.gdpr'; 
            $personne->note = null;
            $personne->civilite = null;
            
            if ($personne->avatar && Storage::disk('public')->exists($personne->avatar)) {
                Storage::disk('public')->delete($personne->avatar);
            }
            $personne->avatar = null;
            $personne->save();

            if ($client) {
                $client->ville = null;
                $client->code_postal = null;
                $client->stripe_id = null; 
                $client->save();
                
                DB::table('a_moyenpaiement')->where('idclient', $client->idclient)->delete();
            }

            $user->delete();

            $animaux = Animal::where('idclient', $client->idclient)->get();
            foreach($animaux as $animal) {
                $animal->nom1animal = 'Animal Supprimé'; 
                $animal->photo = null; 
                $animal->save();
                
            }

            DB::commit();

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/')->with('success', 'Votre compte a été supprimé et vos données personnelles ont été anonymisées.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue lors de la procédure d\'anonymisation : ' . $e->getMessage());
        }
    }

    public function showDeletePage()
    {
        $user = Auth::user();
        $personne = Personne::find($user->idpersonne);
        
        $client = Client::where('idpersonne', $user->idpersonne)->first();
        $animaux = $client ? Animal::where('idclient', $client->idclient)->get() : []; 
        $nbAnimaux = count($animaux);

        return view('clients.delete-account', compact('personne', 'nbAnimaux', 'animaux'));
    }

    public function settings()
    {
        $user = Auth::user();
        $personne = Personne::find($user->idpersonne);
        
        $client = Client::where('idpersonne', $user->idpersonne)->first();
        $nbAnimaux = $client ? Animal::where('idclient', $client->idclient)->count() : 0;

        return view('clients.settings', compact('personne', 'nbAnimaux'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8', 
        ], [
            'current_password.required' => 'Le mot de passe actuel est obligatoire.',
            'password.required'         => 'Le nouveau mot de passe est obligatoire.',
            'password.min'              => 'Le nouveau mot de passe doit contenir au moins :min caractères.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->mdputilisateur)) {
            return back()->withErrors(['current_password' => "L'ancien mot de passe est incorrect."]);
        }

        $user->mdputilisateur = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Mot de passe modifié avec succès !');
    }
    
    public function mesPaiements()
    {
        $user = Auth::user();
        
        $personne = Personne::find($user->idpersonne);

        $client = Client::where('idpersonne', $user->idpersonne)->firstOrFail();

        $cartes = collect(); 

        if ($client->stripe_id) {
            try {
                Stripe::setApiKey(env('STRIPE_SECRET'));
                
                $paymentMethods = Customer::allSources(
                    $client->stripe_id,
                    ['object' => 'card', 'limit' => 10]
                );
                
                $cartes = collect($paymentMethods->data);

            } catch (\Exception $e) {
            }
        }

        $historique = DB::table('commande')
            ->join('facture', 'commande.idfacture', '=', 'facture.idfacture')
            ->where('facture.idclient', $client->idclient)
            ->select('commande.*', DB::raw('(SELECT SUM(q.quantite * p.prixvente) FROM quantite q JOIN produit p ON q.idproduit = p.idproduit WHERE q.idcommande = commande.idcommande) as total'))
            ->orderByDesc('datecommande')
            ->get();

        return view('clients.mes_paiements', compact('cartes', 'historique', 'personne'));
    }

    public function deleteCard($cardId)
    {
        $user = Auth::user();
        $client = Client::where('idpersonne', $user->idpersonne)->firstOrFail();

        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));
            Customer::deleteSource(
                $client->stripe_id,
                $cardId
            );
            return back()->with('success', 'Carte supprimée.');
        } catch (\Exception $e) {
            return back()->with('error', 'Impossible de supprimer la carte.');
        }
    }


    public function deleteSpecificData($field)
    {
        $user = Auth::user();
        $personne = Personne::find($user->idpersonne);
        $client = Client::where('idpersonne', $user->idpersonne)->first();

        try {
            switch ($field) {
                case 'tel':
                    $personne->tel = null;
                    $personne->save();
                    break;

                case 'avatar':
                    if ($personne->avatar && Storage::disk('public')->exists($personne->avatar)) {
                        Storage::disk('public')->delete($personne->avatar);
                    }
                    $personne->avatar = null;
                    $personne->save();
                    break;

                case 'localisation':
                    if ($client) {
                        $client->ville = null;
                        $client->code_postal = null;
                        $client->save();
                    }
                    $personne->adresse = null; 
                    $personne->save();
                    break;

                default:
                    return back()->with('error', 'Ce champ ne peut pas être supprimé.');
            }

            return back()->with('success', 'Information supprimée avec succès.');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }
}