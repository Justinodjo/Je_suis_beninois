<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MediaFactory extends Factory
{
    public function definition(): array
    {
        $type = $this->faker->randomElement(['image', 'video', 'audio']);

        return [
            'titre' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'type' => $type,
            'url' => $this->faker->imageUrl(1920, 1080),
            'url_thumbnail' => $this->faker->imageUrl(400, 300),
            'mime_type' => 'image/jpeg',
            'taille' => $this->faker->numberBetween(10000, 5000000),
            'largeur' => 1920,
            'hauteur' => 1080,
            'duree' => $type === 'video' ? 120 : null,
            'alt_text' => $this->faker->sentence(),
            'credits' => $this->faker->name(),
            'user_id' => null,
        ];
    }
}
