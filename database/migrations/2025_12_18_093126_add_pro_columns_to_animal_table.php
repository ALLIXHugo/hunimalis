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
        Schema::table('animal', function (Blueprint $table) {
            $table->integer('idpro_toiletteur')->nullable();
            $table->integer('idpro_educateur')->nullable();
            $table->integer('idpro_veterinaire')->nullable();
            $table->integer('idpro_pension')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('animal', function (Blueprint $table) {
            $table->dropColumn([
                'idpro_toiletteur', 
                'idpro_educateur', 
                'idpro_veterinaire', 
                'idpro_pension',
            ]);
        });
    }
};
