<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('titre')->nullable();
            $table->text('description')->nullable();
            $table->enum('type', ['image', 'video', 'audio']);
            $table->string('url'); // URL complète
            $table->string('url_thumbnail')->nullable();
            $table->string('mime_type')->nullable();
            $table->bigInteger('taille')->nullable(); // en octets
            $table->integer('largeur')->nullable();
            $table->integer('hauteur')->nullable();
            $table->integer('duree')->nullable(); // en secondes (vidéos)
            $table->string('alt_text')->nullable();
            $table->string('credits')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
            
            $table->index('type');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};