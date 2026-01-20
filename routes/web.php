<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Rutas de autenticación con rate limiting
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->middleware('throttle:login');

    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register'])->middleware('throttle:register');
});

// Logout y otras ruta
Route::post('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Rutas de recuperación de contraseña 
Auth::routes(['login' => false, 'register' => false, 'logout' => false]);


Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // transacciones del usuario
    Route::resource('transactions', TransactionController::class);
    Route::get('/transactions-export', [TransactionController::class, 'export'])->name('transactions.export');

    // proyectos del usuario
    Route::resource('projects', ProjectController::class);

});
