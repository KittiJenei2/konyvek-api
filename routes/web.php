<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\WriterController;
use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;

// Bejelentkezés űrlap és logika
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login'); // Ezt még meg kell írni a Controllerben (csak egy view-t ad vissza)
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Szerzők és könyvek (csak védett útvonalak)
Route::group(['middleware' => 'web'], function () {
    Route::get('/writers', [WriterController::class, 'index'])->name('writers.index');
    // ... többi route ...
});
