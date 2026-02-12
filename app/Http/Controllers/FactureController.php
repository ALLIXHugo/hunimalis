<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Support\Facades\DB;
use App\Models\Facture;
use App\Models\Professionnel;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class FactureController extends Controller
{
    public function valider($id) {
        Facture::where('idfacture', $id)->update(['statut' => 'payee']);
        return back()->with('success', 'Facture marquée comme payée.');
    }

    public function annuler($id) {
        Facture::where('idfacture', $id)->update(['statut' => 'annulee']);
        return back()->with('error', 'Facture annulée.');
    }


    public function creerAvoir($id) {
        $facture = Facture::findOrFail($id);

        if ($facture->statut === 'annulee' || $facture->statut === 'avoir') {
            return back()->with('error', 'Cette facture est déjà annulée ou en avoir.');
        }

        DB::beginTransaction();

        try {
            $facture->statut = 'avoir';
            $facture->save();

            $client = Client::findOrFail($facture->idclient);
            
            $nouveauSolde = ($client->solde_avoir ?? 0) + $facture->total;
            
            $client->solde_avoir = $nouveauSolde;
            $client->save();

            DB::commit();

            return back()->with('success', "Facture annulée. Un avoir de {$facture->total} € a été crédité au client {$client->personne->nom}.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', "Erreur lors de la création de l'avoir : " . $e->getMessage());
        }
    }

    public function envoyerParMail($id) {
        $facture = Facture::with(['client.personne', 'employe.personne'])->findOrFail($id);
        
        $montant = $facture->total; 
        $logoPro = $facture->employe->logo_path ?? 'default-logo.png';
        $pdf = Pdf::loadView('pdf.facture', compact('facture', 'montant', 'logoPro'));

        Mail::send('emails.facture', compact('facture'), function($m) use ($facture, $pdf) {
            $m->to($facture->client->personne->mail)
              ->subject("Facture #" . $facture->idfacture)
              ->attachData($pdf->output(), "facture.pdf");
        });

        return back()->with('success', 'Email envoyé avec succès.');
    }
    public function update(Request $request, $id) {
        $facture = Facture::findOrFail($id);

        if ($facture->statut === 'payee' || $facture->statut === 'annulee') {
            return back()->with('error', 'Impossible de modifier une facture finalisée.');
        }

        $data = $request->only(['nature', 'custom_emetteur']);

        if ($request->hasFile('custom_logo')) {
            if ($facture->custom_logo) {
                Storage::disk('public')->delete($facture->custom_logo);
            }
            
            $path = $request->file('custom_logo')->store('factures/logos', 'public');
            $data['custom_logo'] = $path;
        }

        $facture->update($data);

        return back()->with('success', 'En-tête de la facture mis à jour avec succès.');
    }

    public function downloadPDF($id)
    {
        $facture = Facture::with(['client.personne'])->findOrFail($id);
        $pro = null;

        if ($facture->idcommande) {
            $proData = DB::table('quantite')
                ->join('produit', 'quantite.idproduit', '=', 'produit.idproduit')
                ->where('quantite.idcommande', $facture->idcommande)
                ->whereNotNull('produit.idpro')
                ->select('produit.idpro')
                ->first();
                
            if ($proData) {
                $pro = Professionnel::find($proData->idpro);
            }
        }

        if (!$pro) {
            $proData = DB::table('appartient')
                ->join('rdv', 'appartient.idrdv', '=', 'rdv.idrdv')
                ->join('liepresta', 'rdv.idrdv', '=', 'liepresta.idrdv')
                ->join('prestation', 'liepresta.idprestation', '=', 'prestation.idprestation')
                ->where('appartient.idfacture', $facture->idfacture)
                ->whereNotNull('prestation.idpro')
                ->select('prestation.idpro')
                ->first();

            if (!$proData && !empty($facture->idrdv)) {
                 $proData = DB::table('rdv')
                    ->join('liepresta', 'rdv.idrdv', '=', 'liepresta.idrdv')
                    ->join('prestation', 'liepresta.idprestation', '=', 'prestation.idprestation')
                    ->where('rdv.idrdv', $facture->idrdv)
                    ->whereNotNull('prestation.idpro')
                    ->select('prestation.idpro')
                    ->first();
            }

            if ($proData) {
                $pro = Professionnel::find($proData->idpro);
            }
        }

        if (!$pro && Auth::check()) {
            $userPro = Professionnel::where('idpersonne', Auth::user()->idpersonne)->first();
        }

        $pdf = Pdf::loadView('pdf.facture', compact('facture', 'pro'));

        return $pdf->download('facture_' . $facture->idfacture . '.pdf');
    }
}