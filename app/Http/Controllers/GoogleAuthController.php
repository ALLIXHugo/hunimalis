<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Utilisateur; 
use App\Models\Personne;
use App\Models\Professionnel;
use App\Models\Client; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->redirectUrl(url(env('GOOGLE_REDIRECT_URL'))) 
            ->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            $user = Utilisateur::where('google_id', $googleUser->id)
                            ->orWhere('loginutilisateur', $googleUser->email)
                            ->first();

            if ($user) {
                if (!$user->google_id) {
                    $user->google_id = $googleUser->id;
                    $user->save();
                }
                
                Auth::login($user);
                return redirect()->route('dashboard'); 

            } else {
                
                DB::beginTransaction();

                try {
                    $personne = Personne::where('mail', $googleUser->email)->first();

                    if (!$personne) {
                        $names = explode(' ', $googleUser->name, 2);
                        $personne = Personne::create([
                            'nom' => $names[1] ?? 'Inconnu',
                            'prenom' => $names[0] ?? 'Inconnu',
                            'mail' => $googleUser->email,
                        ]);
                    }

                    $pro = Professionnel::where('idpersonne', $personne->idpersonne)->first();
                    if (!$pro) {
                        Professionnel::create([
                            'idpersonne' => $personne->idpersonne,
                            'libelletypeetablissement' => 'vétérinaire', 
                            'libelleetablissement' => 'Mon Etablissement (Google)',
                            'nompro' => $personne->nom,
                            'prenompro' => $personne->prenom,
                            'melpro' => $personne->mail,
                            'telpro' => '0000000000', 
                        ]);
                    }

                    $newUser = Utilisateur::create([
                        'idpersonne' => $personne->idpersonne,
                        'loginutilisateur' => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'mdputilisateur' => bcrypt(Str::random(16)) 
                    ]);

                    DB::commit();

                    Auth::login($newUser);
                    
                    return redirect()->route('dashboard')->with('success', 'Compte créé via Google !');

                } catch (\Exception $e) {
                    DB::rollBack();
                    return redirect()->route('login')->with('error', "Erreur technique création : " . $e->getMessage());
                }
            }

        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', "Erreur Google : " . $e->getMessage());
        }
    }
}