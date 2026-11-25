<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'login'])
        ->middleware('throttle:30,1')
        ->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)
        ->name('dashboard');

    Route::middleware('auth.session')->group(function () {
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
        Route::patch('/profile', [ProfileController::class, 'update'])->middleware('throttle:10,1')->name('profile.update');
        Route::put('/profile/password', [ProfileController::class, 'password'])->middleware('throttle:10,1')->name('profile.password');
    });

    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');
});

foreach (['teacher', 'student', 'parent', 'school'] as $role) {
    Route::middleware([
        'auth',
        'role:' . $role,
        'permission:access.' . $role,
    ])->prefix($role)->name($role . '.')->group(function () {
        // Keep old portal URLs and names available for existing links.
        Route::get('/', fn () => redirect()->route('dashboard'))
            ->name('dashboard');
    });
}

Route::redirect('/admin/login', '/login');
