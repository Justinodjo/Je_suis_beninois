<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\MediaController;

Route::prefix('v1')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Routes publiques
    |--------------------------------------------------------------------------
    */
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
    Route::post('/register', [UserController::class, 'store'])->middleware('throttle:3,10');

    Route::middleware('throttle:60,1')->group(function () {
        Route::get('/articles', [ArticleController::class, 'index']);
        Route::get('/articles/{article}', [ArticleController::class, 'show']);
        Route::get('/categories', [CategoryController::class, 'index']);
        Route::get('/categories/{category}', [CategoryController::class, 'show']);
        Route::get('/tags', [TagController::class, 'index']);
        Route::get('/tags/{tag}', [TagController::class, 'show']);
        Route::get('/media', [MediaController::class, 'index']);
        Route::get('/media/{media}', [MediaController::class, 'show']);
    });

    /*
    |--------------------------------------------------------------------------
    | Authentifié — tout rôle (visiteur, contributeur, admin)
    |--------------------------------------------------------------------------
    */
    Route::middleware('auth:api')->group(function () {

        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::put('/profile', [UserController::class, 'updateProfile']);

        // Interactions ouvertes à tout compte actif
        Route::post('/articles/{article}/like', [LikeController::class, 'toggle']);
        Route::get('/articles/{article}/like', [LikeController::class, 'status']);
        Route::post('/articles/{article}/comments', [CommentController::class, 'store']);

        /*
        |----------------------------------------------------------------
        | Contributeur + Admin — création/gestion de contenu
        |----------------------------------------------------------------
        */
        Route::middleware('role:contributeur,admin')->group(function () {
            Route::post('/articles', [ArticleController::class, 'store']);
            Route::put('/articles/{article}', [ArticleController::class, 'update']);
            Route::delete('/articles/{article}', [ArticleController::class, 'destroy']);

            Route::post('/media', [MediaController::class, 'store']);
            Route::put('/media/{media}', [MediaController::class, 'update']);
            Route::delete('/media/{media}', [MediaController::class, 'destroy']);
        });

        /*
        |----------------------------------------------------------------
        | Admin uniquement — administration
        |----------------------------------------------------------------
        */
        Route::middleware('role:admin')->group(function () {
            // ✅ users : la faille de tout à l'heure est ici bouchée
            Route::apiResource('users', UserController::class)->except(['store']);

            Route::post('/categories', [CategoryController::class, 'store']);
            Route::put('/categories/{category}', [CategoryController::class, 'update']);
            Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);

            Route::post('/tags', [TagController::class, 'store']);
            Route::put('/tags/{tag}', [TagController::class, 'update']);
            Route::delete('/tags/{tag}', [TagController::class, 'destroy']);

            // Modération des commentaires
            Route::apiResource('comments', CommentController::class)
                ->only(['index', 'show', 'update', 'destroy']);
        });
    });
});

// Route de test
Route::get('/exemple', function () {
    return response()->json(['message' => 'Bonjour, le monde !']);
});