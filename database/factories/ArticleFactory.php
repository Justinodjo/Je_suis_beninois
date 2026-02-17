<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;

class ArticleFactory extends Factory
{
    public function definition(): array
    {
        $title = $this->faker->sentence(6);

        return [
            'titre' => $title,
            'slug' => Str::slug($title) . '-' . rand(1, 9999),
            'extrait' => $this->faker->paragraph(),
            'contenu' => $this->faker->paragraphs(5, true),
            'user_id' => User::inRandomOrder()->first()->id, // ← Rempli avec un utilisateur existant
            'statut' => 'publié',
            'type' => $this->faker->randomElement(['article', 'featured', 'video', 'galerie']),
            'nb_vues' => rand(0, 1000),
            'nb_likes' => rand(0, 200),
            'nb_commentaires' => rand(0, 50),
            'date_publication' => now(),
        ];
    }
}
