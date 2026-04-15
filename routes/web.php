<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\TourismeController;
use App\Http\Controllers\Web\EcommerceController;
use App\Http\Controllers\Web\ActualiteController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\CultureController;
use App\Http\Controllers\Web\InterviewController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\ContributionController;

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

// Page actualités (ancienne home)
Route::get('/actualites', [ActualiteController::class, 'index'])->name('actualites');

// Tourisme
Route::prefix('tourisme')->name('tourisme.')->group(function () {
    Route::get('/', [TourismeController::class, 'index'])->name('index');
});

// E-commerce
Route::prefix('ecommerce')->name('ecommerce.')->group(function () {
    Route::get('/', [EcommerceController::class, 'index'])->name('index');
});
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
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/login', function () {

    if(auth()->check()) {

        $role = auth()->user()->role;

        return match ($role) {
            'admin' => redirect()->route('dashboard.index'),
            'contributeur' => redirect()->route('contribution.index'),
            default => redirect()->route('home'),
        };
    }

    return redirect('/')->with('showLoginModal', true);

})->name('login');

Route::middleware(['auth'])
    ->prefix('contribution')
    ->name('contribution.')
    ->group(function () {
        Route::get('/', [ContributionController::class, 'index'])->name('index');
});
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
        Route::get('/users',      [DashboardController::class, 'users'])->name('users'); // ✅ NOUVEAU

});

// ── Contributeurs ────────────────────────────────
Route::middleware(['auth'])->prefix('contribution')->name('contribution.')->group(function () {
    Route::get('/', [ContributionController::class, 'index'])->name('index');
});
Route::get('/debug-auth', function () {
    return [
        'auth_check' => auth()->check(),
        'auth_user' => auth()->user(),
        'session_id' => session()->getId(),
        'session_data' => session()->all(),
        'guards' => config('auth.guards'),
        'default_guard' => config('auth.defaults.guard'),
    ];
});

