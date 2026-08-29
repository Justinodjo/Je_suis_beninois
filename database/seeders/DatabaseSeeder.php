```php
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
        /*
        |--------------------------------------------------------------------------
        | Vérification
        |--------------------------------------------------------------------------
        |
        | Si la base contient déjà des utilisateurs, on considère
        | qu'elle a déjà été initialisée.
        |
        */

        if (User::count() > 0) {
            $this->command->info('La base contient déjà des données. Seed ignoré.');
            return;
        }

        $this->command->info('Création des données fictives...');

        /*
        |--------------------------------------------------------------------------
        | Administrateur
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

        /*
        |--------------------------------------------------------------------------
        | Utilisateurs
        |--------------------------------------------------------------------------
        */

        $users = User::factory(9)->create();

        $users->push($admin);

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
    }
}
```
