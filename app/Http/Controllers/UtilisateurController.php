<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail; 
use App\Models\Utilisateur;
use App\Models\Professionnel;
use App\Models\Personne;
use App\Models\Client;
use Illuminate\Support\Str;


class UtilisateurController extends Controller
{
    public function showLoginForm()
    {
        return view('utilisateur.connectercompteutilisateur');
    }

    public function showRegisterForm()
    {
        return view('utilisateur.creercompteutilisateur');
    }

    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
            'cgu' => 'required',
        ], [
            'cgu.required' => 'Vous devez accepter les conditions générales.',
            'password.min' => 'Le mot de passe doit faire 6 caractères minimum.'
        ]);

        DB::beginTransaction();
        
        $code = random_int(100000, 999999);

        try {
            $personne = Personne::where('mail', $request->email)->first();
            
            if ($personne) {
                if (Utilisateur::where('idpersonne', $personne->idpersonne)->exists()) {
                    return back()->withInput()->withErrors(['email' => 'Un compte utilisateur existe déjà pour cet email.']);
                }
            } else {
                $personne = Personne::create([
                    'mail' => $request->email, 
                    'nom' => 'A renseigner', 
                    'prenom' => 'A renseigner',
                    'tel' => null 
                ]);
            }

            $user = Utilisateur::create([
                'idpersonne' => $personne->idpersonne,
                'loginutilisateur' => $request->email,
                'mdputilisateur' => Hash::make($request->password),
                'verification_code' => $code
            ]);

            Client::updateOrCreate(
                ['idpersonne' => $personne->idpersonne], 
                [
                    'idutilisateur' => $user->idutilisateur,
                    'nom' => $personne->nom,
                    'prenom' => $personne->prenom,
                    'mail' => $personne->mail
                ]
            );

            try {
                $to = $request->email;
                Mail::raw("Votre code Hunimalis : {$code}", function ($m) use ($to) {
                    $m->to($to)
                      ->subject('Vérification de votre compte')
                      ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
                });
            } catch (\Exception $eMail) {
                DB::rollBack();
                return back()->withInput()->withErrors(['error' => 'Erreur envoi email (Vérifiez votre .env) : ' . $eMail->getMessage()]);
            }

            DB::commit();
            
            return redirect()->route('utilisateur.verifmail', ['id' => $user->idutilisateur])
                             ->with('success', 'Compte créé ! Vérifiez vos emails.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Erreur technique : ' . $e->getMessage()]);
        }
    }


    public function showVerifMail($id)
    {
        $utilisateur = Utilisateur::where('idutilisateur', $id)->firstOrFail();
        
        $especes = DB::table('espece')->get(); 

        return view('utilisateur.verifmail', compact('utilisateur', 'especes'));
    }

    public function verifyMailCode(Request $request)
    {
        $request->validate([
            'idutilisateur' => 'required',
            'code'   => 'required',
            'nom'    => 'required|string|max:50',
            'prenom' => 'required|string|max:50',
            'tel'    => ['required', 'regex:/^(06|07|03|04|01|02|05|09)[0-9]{8}$/'],
        ]);

        $codeClean = trim($request->code);

        $user = Utilisateur::where('idutilisateur', $request->idutilisateur)
                            ->where('verification_code', $codeClean)
                            ->first();

        if ($user) {
            DB::beginTransaction();
            try {
                $user->verification_code = null;
                $user->save();

                DB::table('personne')
                    ->where('idpersonne', $user->idpersonne)
                    ->update([
                        'nom' => $request->nom,
                        'prenom' => $request->prenom,
                        'tel' => $request->tel
                    ]);

                DB::commit();

                Auth::login($user);

                return redirect()->intended(route('home'))
                                ->with('success', 'Compte validé et profil mis à jour !');

            } catch (\Exception $e) {
                DB::rollBack();
                return back()->withInput()->withErrors(['error' => 'Erreur sauvegarde : ' . $e->getMessage()]);
            }
        }

        return back()->withInput()->withErrors(['code' => 'Le code de vérification est incorrect.']);
    }

    public function logout(Request $request)
    {
        Auth::logout(); 
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function updateProfile(\Illuminate\Http\Request $request)
    {
        $user = Auth::user();
        $personne = Personne::find($user->idpersonne);

        if (!$personne) {
            return back()->withErrors(['error' => 'Profil introuvable.']);
        }

        $request->validate([
            'prenom'   => 'required|string|max:100',
            'nom'      => 'required|string|max:100',
            'tel'      => ['required', 'string', 'regex:/^(06|07|03|04|01|02|05|09)[0-9]{8}$/'],
            'avatar'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2Mo
            'civilite' => 'nullable|string',
            'note'     => 'nullable|string',
        ], [
            'tel.regex' => 'Le format du téléphone est invalide (ex: 0612345678).',
            'avatar.image' => 'Le fichier doit être une image valide.',
            'avatar.max' => 'L\'image est trop lourde (max 2Mo).'
        ]);

        $personne->nom = $request->input('nom');
        $personne->prenom = $request->input('prenom');
        $personne->tel = $request->input('tel');

        if ($request->has('civilite')) {
            $personne->civilite = $request->input('civilite');
        }
        
        if ($request->has('note')) {
            $personne->note = $request->input('note');
        }

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $personne->avatar = $path;
        }
        $personne->save();

        Client::where('idpersonne', $user->idpersonne)->update([
            'nom' => $request->input('nom'),
            'prenom' => $request->input('prenom'),
            'tel' => $request->input('tel'),
        ]);

        return back()->with('success', 'Profil mis à jour avec succès !');
    }


public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = [
            'loginutilisateur' => $request->email,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->loginutilisateur === 'admin@hunimalis.com') {
                return redirect()->route('admin.validPro.index');
            }
    
            $pro = Professionnel::where('idpersonne', $user->idpersonne)->first();

            if ($pro && $pro->mfa_active) {
                Auth::logout();

                $code = rand(100000, 999999);
                $pro->mfa_code = (string)$code; 
                $pro->mfa_expires_at = \Carbon\Carbon::now()->addMinutes(10);
                $pro->save();

                try {
                    $basic  = new Basic(env('VONAGE_KEY'), env('VONAGE_SECRET'));
                    $client = new Client($basic);
                    
                    $numero = preg_replace('/[^0-9]/', '', $pro->telpro);
                    if (substr($numero, 0, 1) === '0') $numero = '33' . substr($numero, 1);

                    $client->sms()->send(
                        new SMS($numero, env('VONAGE_FROM', 'Hunimalis'), "Code de connexion : $code")
                    );
                } catch (\Exception $e) {
                    Log::error("Erreur SMS MFA: " . $e->getMessage());
                }

                $request->session()->put('2fa:user_id', $user->idutilisateur);
                return redirect()->route('mfa.index');
            }
    
            return redirect()->route('dashboard'); 
        }

        return back()->withErrors([
            'email' => 'Identifiants incorrects.',
        ])->onlyInput('email');
    }




    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetCode(Request $request)
    {
        $request->validate(['email' => 'required|email']);


        $user = Utilisateur::where('loginutilisateur', $request->email)->first();

        if (!$user) {

            return back()->with('error', 'Si cet email existe, un code a été envoyé.');
        }

        $code = rand(100000, 999999);
        $user->reset_code = $code;
        $user->save();

        try {
            Mail::raw("Votre code de réinitialisation est : $code", function ($message) use ($request) {
                $message->to($request->email)
                        ->subject('Réinitialisation de mot de passe - Hunimalis');
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'envoi du mail.');
        }

        return redirect()->route('password.verify')->with('email', $request->email);
    }

    public function showVerifyCodeForm()
    {
        return view('auth.verify-code');
    }

    public function checkResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|numeric'
        ]);

        $user = Utilisateur::where('loginutilisateur', $request->email)
                        ->where('reset_code', $request->code)
                        ->first();

        if (!$user) {
            return back()->with('error', 'Code invalide ou email incorrect.')->withInput();
        }

        session(['reset_user_id' => $user->idutilisateur]);
        
        return redirect()->route('password.reset.form');
    }

    public function showResetForm()
    {
        if (!session('reset_user_id')) {
            return redirect()->route('password.forgot');
        }
        return view('auth.reset-password');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed', 
        ], [
            'password.min' => 'Le mot de passe doit faire 8 caractères minimum.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.'
        ]);

        $userId = session('reset_user_id');
        
        if (!$userId) {
            return redirect()->route('password.forgot')->with('error', 'Session expirée.');
        }

        $user = Utilisateur::find($userId);
        $user->mdputilisateur = Hash::make($request->password);
        $user->reset_code = null; 
        $user->save();

        $request->session()->forget('reset_user_id');

        return redirect()->route('login')->with('success', 'Mot de passe modifié ! Connectez-vous.');
    }
}
