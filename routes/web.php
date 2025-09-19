<?php

use App\Http\Controllers\ArticleController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

// Cikkek listázása
Route::get('articles', [ArticleController::class, 'index'])->name('article.index');

//Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

    Route::get('article/{article}', [ArticleController::class, 'show'])->name('article.show');
//});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
