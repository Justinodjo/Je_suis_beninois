<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\CultureController;
use App\Http\Controllers\Web\InterviewController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\InteractionController;
use App\Http\Controllers\Web\MediaController;
use App\Http\Controllers\Web\ContactController;

/*
|--------------------------------------------------------------------------
| Routes Web - Je Suis Béninois
|--------------------------------------------------------------------------
|
| Ces routes servent uniquement les pages Blade.
| L'authentification est gérée par JWT via l'API.
|
*/


/*
|--------------------------------------------------------------------------
| Pages publiques
|--------------------------------------------------------------------------
*/

// Page d'accueil
Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::get('/actualites', [HomeController::class, 'actualites'])
    ->name('actualites');

// Galerie
Route::get('/galerie', [MediaController::class, 'index'])
    ->name('galerie');

// Contact
Route::get('/contact', [ContactController::class, 'index'])
    ->name('contact');

Route::post('/contact', [ContactController::class, 'store'])
    ->name('contact.store');

// Financer
Route::view('/financer', 'pages.financer')
    ->name('financer');

// Pages Culture & Patrimoine
Route::prefix('culture')
    ->name('culture.')
    ->group(function () {

        Route::get('/', [CultureController::class, 'index'])
            ->name('index');

        Route::get('/traditions', [CultureController::class, 'traditions'])
            ->name('traditions');

        Route::get('/patrimoine', [CultureController::class, 'patrimoine'])
            ->name('patrimoine');

        Route::get('/article/{slug}', [CultureController::class, 'show'])
            ->name('article');
    });


// Interviews
Route::prefix('interviews')
    ->name('interviews.')
    ->group(function () {

        Route::get('/', [InterviewController::class, 'index'])
            ->name('index');

        Route::get('/{slug}', [InterviewController::class, 'show'])
            ->name('show');
    });


/*
|--------------------------------------------------------------------------
| Login
|--------------------------------------------------------------------------
|
| Le login réel est effectué par :
|
| POST /api/v1/login
|
| Cette route redirige simplement vers l'accueil.
|
*/
Route::get('/login', [AuthController::class, 'login'])
    ->name('login');

Route::get('/register', [AuthController::class, 'register'])
    ->name('register');


/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
|
| La vérification JWT est faite côté JavaScript avec :
|
| GET /api/v1/me
|
| Authorization: Bearer TOKEN
|
*/

Route::prefix('dashboard')
    ->name('dashboard.')
    ->group(function () {

        Route::get('/', [DashboardController::class, 'index'])
            ->name('index');

        Route::get('/articles', [DashboardController::class, 'articles'])
            ->name('articles');

        Route::get('/categories', [DashboardController::class, 'categories'])
            ->name('categories');

        Route::get('/tags', [DashboardController::class, 'tags'])
            ->name('tags');

        Route::get('/media', [DashboardController::class, 'media'])
            ->name('media');

        Route::get('/stats', [DashboardController::class, 'stats'])
            ->name('stats');

        Route::get('/users', [DashboardController::class, 'users'])
            ->name('users');
    });