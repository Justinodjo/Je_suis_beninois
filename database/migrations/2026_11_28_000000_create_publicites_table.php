<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('publicites', function (Blueprint $table) {
            $table->id();
            $table->string('nom');                         // nom de l'annonceur
            $table->string('emplacement', 30);             // banniere_principale | laterale | banniere_contenu
            $table->string('image');                       // chemin sur le disque "public"
            $table->string('lien')->nullable();            // URL de destination (http/https)
            $table->string('texte_alt')->nullable();
            $table->unsignedInteger('ordre')->default(0);  // ordre dans le slider
            $table->string('statut', 10)->default('actif');// actif | inactif
            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();
            $table->unsignedBigInteger('nb_clics')->default(0);
            $table->timestamps();

            $table->index(['emplacement', 'statut']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publicites');
    }
};
