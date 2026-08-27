<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();

            $table->string('titre');
            $table->string('slug')->unique();
            $table->text('extrait')->nullable();
            $table->longText('contenu');

            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->enum('statut', ['brouillon', 'publié', 'archivé'])
                  ->default('brouillon');

            $table->enum('type', ['article', 'tradition', 'patrimoine','interview', 'featured', 'galerie', 'video'])->default('article');

            $table->unsignedInteger('nb_vues')->default(0);
            $table->unsignedInteger('nb_likes')->default(0);
            $table->unsignedInteger('nb_commentaires')->default(0);

            $table->timestamp('date_publication')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Index importants
            $table->index('slug');
            $table->index('statut');
            $table->index('type');
            $table->index('date_publication');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
