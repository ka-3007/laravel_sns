<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterUserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    // 登録ページ（新規アカウント作成）
    Route::get('register', [RegisterUserController::class, 'create'])
        ->name('register');
    Route::post('register', [RegisterUserController::class, 'store']);
    //google登録（新規アカウント作成）
    Route::prefix('register')->name('register.')->group(function () {
        Route::get('/{provider}', [RegisterUserController::class, 'showProviderUserRegistrationForm'])->name('{provider}');
        Route::post('/{provider}', [RegisterUserController::class, 'registerProviderUser'])->name('{provider}');
    });

    // ログインページ
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    //googleログイン
    Route::prefix('login')->name('login.')->group(function () {
        Route::get('/{provider}', [LoginController::class, 'redirectToProvider'])->name('{provider}');
        Route::get('/{provider}/callback', [LoginController::class, 'handleProviderCallback'])->name('{provider}.callback');
    });


    // パスワードリセット関連
    Route::get('forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])
        ->name('password.request');
    Route::post('forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])
        ->name('password.email');
    Route::get('reset-password/{token}', [PasswordResetController::class, 'showResetForm'])
        ->name('password.reset');
    Route::post('reset-password', [PasswordResetController::class, 'reset'])
        ->name('password.update');
});

Route::middleware('auth')->group(function () {
    // ログアウト
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
