<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\ValidationRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AdminValidationController extends Controller
{
    public function index()
    {
        $requests = ValidationRequest::with('professionnel')
                    ->where('statut', 'en_attente')
                    ->orderBy('date_proposee', 'asc')
                    ->get();

        return view('rdv.validation.index', compact('requests'));
    }

    public function accept($id)
    {
        $requestVal = ValidationRequest::with('professionnel')->findOrFail($id);
        $requestVal->update(['statut' => 'valide']);

        $this->sendValidationEmail($requestVal, true);
        
        return redirect()->back()->with('success', 'Le rendez-vous a été confirmé et le professionnel notifié.');
    }

    public function refuse($id)
    {
        $requestVal = ValidationRequest::with('professionnel')->findOrFail($id);
        $requestVal->update(['statut' => 'refuse']);

        $this->sendValidationEmail($requestVal, false);

        return redirect()->back()->with('success', 'Le rendez-vous a été refusé.');
    }


    private function sendValidationEmail($requestVal, $isAccepted)
    {
        if (!$requestVal->professionnel || !$requestVal->professionnel->melpro) {
            return;
        }

        $to = $requestVal->professionnel->melpro;
        $subject = 'Confirmation de rendez-vous Hunimalis';
        $date = Carbon::parse($requestVal->date_proposee)->format('d/m/Y');
        $heure = Carbon::parse($requestVal->heure_proposee)->format('H:i');
        $nomPro = $requestVal->professionnel->nompro;

        $statusText = $isAccepted 
            ? "a été acceptée par l'équipe Hunimalis.\n\nUn administrateur vous contactera à ce moment-là." 
            : "a été refusée par l'équipe Hunimalis.";

        $messageContent = "Bonjour {$nomPro},\n\n" .
                          "Votre demande de rendez-vous pour la validation de votre société {$statusText}\n\n" .
                          "Détails du rendez-vous :\n" .
                          "- Date : {$date}\n" .
                          "- Heure : {$heure}\n\n" .
                          "Cordialement,\nL'équipe Hunimalis";

        try {
            Mail::raw($messageContent, function ($message) use ($to, $subject) {
                $message->to($to)->subject($subject);
            });
        } catch (\Exception $e) {
            Log::error("Échec envoi email à {$to} : " . $e->getMessage());
        }
    }
}