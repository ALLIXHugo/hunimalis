<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// Imports manquants ajoutés
use App\Models\Client;
use App\Models\Commande;
use App\Models\Quantite;
use App\Models\RDV; // Assurez-vous que votre modèle s'appelle bien RDV.php ou Rendezvous.php

class Facture extends Model
{
    protected $table = 'facture';
    protected $primaryKey = 'idfacture';
    public $timestamps = false;

    // Champs remplissables pour le Controller
    protected $fillable = [
        'idpersonne', 
        'idclient', 
        'idrdv', 
        'idcommande', 
        'date_facture', 
        'statut', 
        'nature', 
        'custom_emetteur', 
        'custom_logo'
    ];
    
    // 1. Relation Client
    public function client() {
        // On précise les clés car votre SQL utilise des clés composites parfois
        return $this->belongsTo(Client::class, 'idclient', 'idclient');
    }

    // 2. Relation RDV (Correction: c'est la facture qui porte l'ID du RDV)
    public function rendezvous() {
        return $this->belongsTo(RDV::class, 'idrdv', 'idrdv');
    }

    // 3. Relation Commande (Correction: c'est la facture qui porte l'ID commande)
    public function commande() {
        return $this->belongsTo(Commande::class, 'idcommande', 'idcommande');
    }

    // 4. Calcul du total
    public function getTotalAttribute() {
        $total = 0;

        // Si la facture est liée à un RDV (Prestation)
        // On vérifie relation -> prestation
        if ($this->rendezvous && $this->rendezvous->liepresta && $this->rendezvous->liepresta->prestation) {
            // Note: Adaptez selon votre modèle RDV, souvent le lien est direct ou via 'prestation'
            // D'après votre SQL, le lien est dans la table 'liepresta' ou 'prestation' directe.
            // Si le modèle RDV a une relation 'prestation':
             $total += $this->rendezvous->prestation->tarifht ?? 0;
        }
        // Fallback si la relation est différente dans votre code RDV
        elseif ($this->rendezvous) {
             // Essayons de récupérer via une jointure si le modèle est complexe
             $presta = \DB::table('liepresta')
                        ->join('prestation', 'liepresta.idprestation', '=', 'prestation.idprestation')
                        ->where('liepresta.idrdv', $this->idrdv)
                        ->first();
             if($presta) $total += $presta->tarifht;
        }

        // Si la facture est liée à une commande
        if ($this->commande) {
            // Calcul de la somme : (prixvente * quantite)
            // On suppose que le modèle Commande a une relation 'produits' ou 'quantites'
            // Sinon on le fait en brut pour être sûr :
            $totalProduits = \DB::table('quantite')
                ->join('produit', 'quantite.idproduit', '=', 'produit.idproduit')
                ->where('quantite.idcommande', $this->idcommande)
                ->sum(\DB::raw('produit.prixvente * quantite.quantite'));
            
            $total += $totalProduits;
        }

        return $total;
    }
}