<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Utilisateur;
use App\Models\Professionnel;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MfaController extends Controller
{
    public function index()
    {
        if (!session()->has('2fa:user_id')) {
            return redirect()->route('login');
        }
        return view('professionnel.mfa_verify');
    }

    public function verify(Request $request)
    {
        $request->validate(['code' => 'required']);

        $userId = session('2fa:user_id');
        $user = Utilisateur::find($userId);

        if (!$user) return redirect()->route('login');

        $pro = Professionnel::where('idpersonne', $user->idpersonne)->first();

        if ($pro && $pro->mfa_code === $request->code && Carbon::parse($pro->mfa_expires_at)->gt(Carbon::now())) {
            
            $pro->mfa_code = null;
            $pro->mfa_expires_at = null;
            $pro->save();

            Auth::login($user);
            session()->forget('2fa:user_id');

            return redirect()->route('pro.dashboard')->with('success', 'Connexion sécurisée réussie !');
        }

        return back()->withErrors(['code' => 'Code invalide ou expiré.']);
    }
}