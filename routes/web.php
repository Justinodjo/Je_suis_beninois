<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\CultureController;
use App\Http\Controllers\Web\InterviewController;
use App\Http\Controllers\Web\DashboardController;

/*
|--------------------------------------------------------------------------
| Routes Web - Je Suis Béninois
|--------------------------------------------------------------------------
|
| Routes publiques du site web
|
*/

// Page d'accueil
Route::get('/', [HomeController::class, 'index'])->name('home');

// Pages Culture & Patrimoine
Route::prefix('culture')->name('culture.')->group(function () {
    Route::get('/', [CultureController::class, 'index'])->name('index');
    Route::get('/traditions', [CultureController::class, 'traditions'])->name('traditions');
    Route::get('/patrimoine', [CultureController::class, 'patrimoine'])->name('patrimoine');
    Route::get('/article/{slug}', [CultureController::class, 'show'])->name('article');
});

// Interviews
Route::prefix('interviews')->name('interviews.')->group(function () {
    Route::get('/', [InterviewController::class, 'index'])->name('index');
    Route::get('/{slug}', [InterviewController::class, 'show'])->name('show');
});

Route::get('/login', function () {
    // Si l'utilisateur est déjà connecté, on ne redirige pas
    if(auth()->check()) {
        return redirect()->intended('/dashboard'); // ou dashboard.index
    }

    // Sinon, redirige vers l'accueil et affiche le modal
    return redirect('/')->with('showLoginModal', true);
})->name('login');


// Dashboard Admin (protégé par authentification + rôle admin)
Route::middleware(['auth', 'admin'])
    ->prefix('dashboard')
    ->name('dashboard.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('index');
        Route::get('/articles', [DashboardController::class, 'articles'])->name('articles');
        Route::get('/categories', [DashboardController::class, 'categories'])->name('categories');
        Route::get('/tags', [DashboardController::class, 'tags'])->name('tags');
        Route::get('/media', [DashboardController::class, 'media'])->name('media');
        Route::get('/stats', [DashboardController::class, 'stats'])->name('stats');
});



