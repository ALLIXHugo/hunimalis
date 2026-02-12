<?php

namespace App\Http\Controllers;

use App\Models\Professionnel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AdminProController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->check() && auth()->user()->loginutilisateur === 'admin@hunimalis.com') {
                return $next($request);
            }
            abort(403, 'Accès réservé aux administrateurs.');
        });
    }

    public function index()
    {
        $demandes = Professionnel::where('statut', 2)
            ->orderBy('idpro', 'desc')
            ->get();

        return view('admin.validPro', compact('demandes'));
    }

    public function accept($id)
    {
        $pro = Professionnel::findOrFail($id);
        $pro->update(['statut' => 1]);

        $this->sendAccountStatusEmail($pro, true);

        return back()->with('success', "Le compte de {$pro->nompro} a été validé et le mail envoyé.");
    }

    public function refuse($id)
    {
        $pro = Professionnel::findOrFail($id);
        $pro->update(['statut' => 3]);

        $this->sendAccountStatusEmail($pro, false);

        return back()->with('warning', "Le compte de {$pro->nompro} a été refusé et le mail envoyé.");
    }


    private function sendAccountStatusEmail($pro, $isAccepted)
    {
        if (!$pro || !$pro->melpro) {
            return;
        }

        $to = $pro->melpro;
        $nomPro = $pro->nompro . ' ' . $pro->prenompro;
        
        if ($isAccepted) {
            $subject = 'Bienvenue sur Hunimalis ! Votre compte est validé';
            $messageContent = "Bonjour {$nomPro},\n\n" .
                              "Félicitations ! Nous avons le plaisir de vous annoncer que votre compte professionnel Hunimalis a été validé par notre équipe administrative.\n\n" .
                              "Vous pouvez désormais vous connecter et accéder à l'ensemble de vos fonctionnalités (Agenda, Facturation, Stock...).\n\n" .
                              "Lien de connexion : http://51.83.36.122.nip.io:2004/login \n\n" .
                              "Cordialement,\nL'équipe Hunimalis";
        } else {
            $subject = 'Mise à jour concernant votre inscription Hunimalis';
            $messageContent = "Bonjour {$nomPro},\n\n" .
                              "Nous avons bien reçu votre demande d'inscription.\n\n" .
                              "Après étude de votre dossier, nous sommes au regret de vous informer que nous ne pouvons pas valider votre compte professionnel pour le moment.\n\n" .
                              "Cela peut être dû à des informations manquantes ou non conformes. N'hésitez pas à contacter le support pour plus de détails.\n\n" .
                              "Cordialement,\nL'équipe Hunimalis";
        }

        try {
            Mail::raw($messageContent, function ($message) use ($to, $subject) {
                $message->to($to)
                        ->subject($subject)
                        ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            });
        } catch (\Exception $e) {
            Log::error("Échec envoi email compte pro à {$to} : " . $e->getMessage());
        }
    }
}