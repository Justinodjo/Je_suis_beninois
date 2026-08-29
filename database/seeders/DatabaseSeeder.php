<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Media;
use App\Models\Article;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 🔐 Admin par défaut
       $admin = User::updateOrCreate(
    ['email' => 'admin@example.com'],
    [
        'name' => 'Administrateur',
        'password' => Hash::make('password123'),
        'role' => 'admin',
        'statut' => 'actif',
        'avatar' => null,
        'bio' => 'Administrateur principal du site',
        'date_inscription' => now(),
    ]
);

    // ✍️ Contributeur
        $contributeur = User::updateOrCreate(
            ['email' => 'contributeur@example.com'],
            [
                'name' => 'Contributeur',
                'password' => Hash::make('password123'),
                'role' => 'contributeur',
                'statut' => 'actif',
                'avatar' => null,
                'bio' => 'Contributeur du site',
                'date_inscription' => now(),
            ]
        );

        // 👤 Visiteur
        $visiteur = User::updateOrCreate(
            ['email' => 'visiteur@example.com'],
            [
                'name' => 'Visiteur',
                'password' => Hash::make('password123'),
                'role' => 'visiteur',
                'statut' => 'actif',
                'avatar' => null,
                'bio' => 'Utilisateur visiteur du site',
                'date_inscription' => now(),
            ]
        );

        // 👥 Autres utilisateurs
        $users = User::factory(9)->create();
        $users->push($admin);
        $users->push($contributeur);
        $users->push($visiteur);

        // 📂 Catégories
        $categories = Category::factory(5)->create();

        // 🏷️ Tags
        $tags = Tag::factory(10)->create();

        // 🖼️ Médias
        $media = Media::factory(20)->create();

        // 📝 Articles
        Article::factory(15)->create()->each(function ($article) use ($categories, $tags, $media, $users) {

            // Assigner auteur aléatoire
            $article->update([
                'user_id' => $users->random()->id
            ]);

            // Attacher catégories
            $article->categories()->attach(
                $categories->random(rand(1, 3))->pluck('id')->toArray()
            );

            // Attacher tags
            $article->tags()->attach(
                $tags->random(rand(2, 5))->pluck('id')->toArray()
            );

            // ✅ Relation correcte (media et non medias)
            $article->medias()->attach($media->random(rand(1,3))->pluck('id')->toArray());

        });


          // ℹ️ Informations
        $this->command->info('Seed terminé avec succès.');
        $this->command->info('');
        $this->command->info('ADMIN');
        $this->command->info('Email : admin@example.com');
        $this->command->info('Mot de passe : password123');
        $this->command->info('');
        $this->command->info('CONTRIBUTEUR');
        $this->command->info('Email : contributeur@example.com');
        $this->command->info('Mot de passe : password123');
        $this->command->info('');
        $this->command->info('VISITEUR');
        $this->command->info('Email : visiteur@example.com');
        $this->command->info('Mot de passe : password123');
    }
}
