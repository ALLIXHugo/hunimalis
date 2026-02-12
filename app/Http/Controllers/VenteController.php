<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Produit;
use App\Models\CodePromo;
use Carbon\Carbon;

class VenteController extends Controller
{
    public function checkPromo(Request $request)
    {
        $user = Auth::user();
        $pro = Professionnel::where('idpersonne', $user->idpersonne)->first();
        if (!$pro) return response()->json(['valid' => false, 'message' => 'Pro non identifié']);

        $code = CodePromo::where('code', $request->code)->where('idpro', $pro->idpro)->first();

        if (!$code) return response()->json(['valid' => false, 'message' => 'Code introuvable.']);
        if ($code->date_fin && Carbon::parse($code->date_fin)->endOfDay()->isPast()) return response()->json(['valid' => false, 'message' => 'Expiré.']);

        return response()->json([
            'valid' => true, 
            'type' => $code->type, 
            'valeur' => $code->valeur, 
            'message' => 'Code appliqué !'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'idclient' => 'required|exists:client,idclient',
            'produits' => 'required|array|min:1',
            'produits.*.id' => 'required|exists:produit,idproduit',
            'produits.*.qty' => 'required|integer|min:1',
            'code_promo' => 'nullable|string' 
        ]);

        try {
            DB::beginTransaction();

            $user = Auth::user();
            $pro = Professionnel::where('idpersonne', $user->idpersonne)->firstOrFail();
            
            
            $totalProduits = 0;
            foreach ($request->produits as $item) {
                $produit = Produit::where('idproduit', $item['id'])
                                  ->where('idpro', $pro->idpro)
                                  ->lockForUpdate()
                                  ->firstOrFail();

                if ($produit->stocks < $item['qty']) {
                    throw new \Exception("Stock insuffisant pour " . $produit->nomproduit);
                }
                
                $totalProduits += $produit->prixvente * $item['qty'];
            }

            $montantRemise = 0;
            if ($request->filled('code_promo')) {
                $promo = CodePromo::where('code', $request->code_promo)
                                  ->where('idpro', $pro->idpro)
                                  ->first();
                
                if ($promo && (!$promo->date_fin || Carbon::parse($promo->date_fin)->endOfDay()->isFuture())) {
                    if ($promo->type === 'pourcentage') {
                        $montantRemise = $totalProduits * ($promo->valeur / 100);
                    } else {
                        $montantRemise = $promo->valeur;
                    }
                }
            }

            $prixFinal = max(0, $totalProduits - $montantRemise);

            $clientData = DB::table('client')->where('idclient', $request->idclient)->first();
            $idPersonneFacture = $clientData->idpersonne ?? $user->idpersonne;

            $idFacture = DB::table('facture')->insertGetId([
                'idpersonne' => $idPersonneFacture,
                'idclient'   => $request->idclient,
                'idcommande' => null
            ], 'idfacture');

            $idCommande = DB::table('commande')->insertGetId([
                'idfacture'    => $idFacture,
                'datecommande' => now()
            ], 'idcommande');

            DB::table('facture')->where('idfacture', $idFacture)->update(['idcommande' => $idCommande]);

            foreach ($request->produits as $item) {
                DB::table('quantite')->insert([
                    'idproduit' => $item['id'], 
                    'idcommande' => $idCommande, 
                    'quantite' => $item['qty']
                ]);
                
                DB::table('produit')->where('idproduit', $item['id'])->decrement('stocks', $item['qty']);
            }

            DB::commit();
            
            $msg = 'Vente validée ! Net à payer : ' . number_format($prixFinal, 2) . ' €';
            if($montantRemise > 0) {
                $msg .= ' (dont remise de ' . number_format($montantRemise, 2) . ' €)';
            }

            return back()->with('success', $msg);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }
}