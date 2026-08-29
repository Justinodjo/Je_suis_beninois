```php
<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Media;
use App\Models\Article;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | ⚠️ RESET COMPLET DES DONNÉES
        |--------------------------------------------------------------------------
        | Cette partie supprime toutes les données avant le seed.
        | CASCADE permet de supprimer également les données liées
        | dans les tables pivot et les tables ayant des clés étrangères.
        */

        DB::statement('
            TRUNCATE TABLE
                article_category,
                article_tag,
                article_media,
                comments,
                likes,
                medias,
                articles,
                tags,
                categories,
                personal_access_tokens,
                users
            RESTART IDENTITY CASCADE
        ');

        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */

        $admin = User::create([
            'name' => 'Administrateur',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'statut' => 'actif',
            'avatar' => null,
            'bio' => 'Administrateur principal du site',
            'date_inscription' => now(),
        ]);

        // ✍️ Contributeur
        $contributeur = User::create([
            'name' => 'Contributeur',
            'email' => 'contributeur@example.com',
            'password' => Hash::make('password123'),
            'role' => 'contributeur',
            'statut' => 'actif',
            'avatar' => null,
            'bio' => 'Contributeur du site',
            'date_inscription' => now(),
        ]);

        // 👤 Visiteur
        $visiteur = User::create([
            'name' => 'Visiteur',
            'email' => 'visiteur@example.com',
            'password' => Hash::make('password123'),
            'role' => 'visiteur',
            'statut' => 'actif',
            'avatar' => null,
            'bio' => 'Utilisateur visiteur du site',
            'date_inscription' => now(),
        ]);
        /*
        |--------------------------------------------------------------------------
        | UTILISATEURS
        |--------------------------------------------------------------------------
        */

       // 👥 Utilisateurs fictifs supplémentaires
        $users = User::factory(9)->create();

        // Tous les utilisateurs
        $users->push($admin);
        $users->push($contributeur);
        $users->push($visiteur);

        // Utilisateurs autorisés à créer des articles
        $authors = collect([
            $admin,
            $contributeur,
        ]);

        /*
        |--------------------------------------------------------------------------
        | CATÉGORIES
        |--------------------------------------------------------------------------
        */

        $categories = Category::factory(5)->create();

        /*
        |--------------------------------------------------------------------------
        | TAGS
        |--------------------------------------------------------------------------
        */

        $tags = Tag::factory(10)->create();

        /*
        |--------------------------------------------------------------------------
        | MÉDIAS
        |--------------------------------------------------------------------------
        */

        $media = Media::factory(20)->create();

        /*
        |--------------------------------------------------------------------------
        | ARTICLES
        |--------------------------------------------------------------------------
        */

        Article::factory(15)->create()->each(function ($article) use (
            $categories,
            $tags,
            $media,
            $users
        ) {

            // Auteur aléatoire
            $article->update([
                'user_id' => $authors->random()->id,
            ]);

            // Catégories aléatoires
            $article->categories()->attach(
                $categories
                    ->random(rand(1, 3))
                    ->pluck('id')
                    ->toArray()
            );

            // Tags aléatoires
            $article->tags()->attach(
                $tags
                    ->random(rand(2, 5))
                    ->pluck('id')
                    ->toArray()
            );

            // Médias aléatoires
            $article->medias()->attach(
                $media
                    ->random(rand(1, 3))
                    ->pluck('id')
                    ->toArray()
            );
        });

        /*
        |--------------------------------------------------------------------------
        | MESSAGE
        |--------------------------------------------------------------------------
        */

        $this->command->info('Base de données réinitialisée et remplie avec succès.');
        $this->command->info('Admin : admin@example.com');
        $this->command->info('Mot de passe : password123');
    }
}
