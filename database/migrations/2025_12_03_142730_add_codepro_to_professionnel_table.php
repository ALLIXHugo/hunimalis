<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('professionnel', function (Blueprint $table) {
            // Ajout de la colonne pour le code de vérification (6 chiffres)
            // 'nullable()' est crucial car les enregistrements existants n'auront pas de valeur.
            $table->string('codepro', 6)->nullable()->after('statut'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('professionnel', function (Blueprint $table) {
            // Pour l'annulation, on supprime la colonne
            $table->dropColumn('codepro');
        });
    }
};