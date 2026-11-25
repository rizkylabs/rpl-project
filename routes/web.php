<?php

use App\Http\Controllers\Auth\LoginController;
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
    Route::get('/dashboard', [LoginController::class, 'dashboard'])
        ->name('dashboard');

    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');
});

foreach (['teacher', 'student', 'parent', 'school'] as $role) {
    Route::middleware([
        'auth',
        'role:' . $role,
        'permission:access.' . $role,
    ])->prefix($role)->name($role . '.')->group(function () use ($role) {
        Route::view('/', $role . '.dashboard')
            ->name('dashboard');
    });
}

Route::redirect('/admin/login', '/login');
