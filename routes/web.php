<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;

Route::get('/', function () {
    return redirect()->route('login');
});


Auth::routes();


Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    // transacciones del usuario
    Route::resource('transactions', TransactionController::class); 

    // proyectos del usuario
    Route::resource('projects', ProjectController::class);

});
