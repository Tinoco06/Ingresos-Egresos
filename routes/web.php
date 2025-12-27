<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

Route::get('/home', function() {return redirect()->route('transactions.index');})->name('home');

Route::middleware('auth')->group(function () {

    // Rutas para las transacciones
    Route::get('/', function(){
        return redirect()->route('transactions.index');
    });

    // CRUD completo
    Route::resource('transactions', TransactionController::class); 

});
