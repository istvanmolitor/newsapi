<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\KeywordController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\PortalController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Cikkek listázása
Route::get('/', [ArticleController::class, 'index'])->name('article.index');
Route::get('keywords', [KeywordController::class, 'index'])->name('keyword.index');
Route::get('keyword/{keyword}', [KeywordController::class, 'show'])->name('keyword.show');

// Portálok
Route::get('portals', [PortalController::class, 'index'])->name('portal.index');
Route::get('portals/{portal}', [PortalController::class, 'show'])->name('portal.show');

// Egyszerű naptár
Route::get('calendar', [CalendarController::class, 'index'])->name('calendar.index');
Route::get('calendar/day/{date}', [CalendarController::class, 'day'])
    ->where('date', '\\d{4}-\\d{2}-\\d{2}')
    ->name('calendar.day');

//Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

    Route::get('article/{article}', [ArticleController::class, 'show'])->name('article.show');
//});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
