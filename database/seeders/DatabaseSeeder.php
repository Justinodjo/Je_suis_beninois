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
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Nettoyage de la base avant remplissage...');

        $this->wipeDatabase();

        $this->command->info('Création des données fictives...');

        /*
        |--------------------------------------------------------------------------
        | Utilisateurs de démonstration (rôles fixes)
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

        $contributeur = User::create([
            'name' => 'Contributeur Démo',
            'email' => 'contributeur@example.com',
            'password' => Hash::make('password123'),
            'role' => 'contributeur',
            'statut' => 'actif',
            'avatar' => null,
            'bio' => 'Compte contributeur de démonstration',
            'date_inscription' => now(),
        ]);

        $visiteur = User::create([
            'name' => 'Visiteur Démo',
            'email' => 'visiteur@example.com',
            'password' => Hash::make('password123'),
            'role' => 'visiteur',
            'statut' => 'actif',
            'avatar' => null,
            'bio' => 'Compte visiteur de démonstration',
            'date_inscription' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Utilisateurs supplémentaires (factory)
        |--------------------------------------------------------------------------
        */

        $users = User::factory(9)->create();

        $users->push($admin);
        $users->push($contributeur);
        $users->push($visiteur);

        /*
        |--------------------------------------------------------------------------
        | Catégories
        |--------------------------------------------------------------------------
        */

        $categories = Category::factory(5)->create();

        /*
        |--------------------------------------------------------------------------
        | Tags
        |--------------------------------------------------------------------------
        */

        $tags = Tag::factory(10)->create();

        /*
        |--------------------------------------------------------------------------
        | Médias
        |--------------------------------------------------------------------------
        */

        $media = Media::factory(20)->create();

        /*
        |--------------------------------------------------------------------------
        | Articles
        |--------------------------------------------------------------------------
        */

        $articles = Article::factory(15)->create();

        $articles->each(function ($article) use (
            $categories,
            $tags,
            $media,
            $users
        ) {

            // Auteur aléatoire
            $article->update([
                'user_id' => $users->random()->id,
            ]);

            // Catégories
            $article->categories()->attach(
                $categories
                    ->random(rand(1, 3))
                    ->pluck('id')
                    ->toArray()
            );

            // Tags
            $article->tags()->attach(
                $tags
                    ->random(rand(2, 5))
                    ->pluck('id')
                    ->toArray()
            );

            // Médias
            $article->medias()->attach(
                $media
                    ->random(rand(1, 3))
                    ->pluck('id')
                    ->toArray()
            );
        });

        $this->command->info('Données fictives créées avec succès !');
        $this->command->info('  - admin@example.com / password123 (admin)');
        $this->command->info('  - contributeur@example.com / password123 (contributeur)');
        $this->command->info('  - visiteur@example.com / password123 (visiteur)');
    }

    /**
     * Vide toutes les tables de données métier avant le reseed,
     * sans toucher aux migrations elles-mêmes ni aux sessions actives.
     */
    protected function wipeDatabase(): void
    {
        Schema::disableForeignKeyConstraints();

        // ── Tables pivots (many-to-many) ──
        // Vérifiées une par une : si un nom diffère chez toi, il est
        // simplement ignoré au lieu de faire planter le seed.
        $pivotTables = [
            'article_category',
            'article_tag',
            'article_media',
            'category_article',
            'tag_article',
            'media_article',
        ];

        foreach ($pivotTables as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->truncate();
            }
        }

        // ── Tables principales ──
        $mainTables = [
            'comments',
            'articles',
            'media',
            'tags',
            'categories',
            'users',
        ];

        foreach ($mainTables as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->truncate();
            }
        }

        Schema::enableForeignKeyConstraints();
    }
}