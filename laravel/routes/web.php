<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ArticleController::class, 'index'])->name('articles.index');
Route::middleware('auth')->group(function () {
    Route::resource('/articles', ArticleController::class)->except(['index', 'show']);
});
Route::resource('/articles', ArticleController::class)->only(['show']);

Route::prefix('articles')->name('articles.')->middleware('auth')->group(function () {
    Route::put('/{article}/like', [ArticleController::class, 'like'])->name('like');
    Route::delete('/{article}/like', [ArticleController::class, 'unlike'])->name('unlike');
});

require __DIR__ . '/auth.php';
