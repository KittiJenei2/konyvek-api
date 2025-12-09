<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WriterController;
use App\Http\Controllers\BookController; 

Route::get('/', function () {
    return redirect()->route('writers.index');
});

// Auth útvonalak
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Szerzők
Route::resource('writers', WriterController::class);

// Könyvek (Szerzőhöz kapcsolva)
// EZ A SOR HIÁNYOZHAT NÁLAD:
Route::get('/writers/{author_id}/books', [BookController::class, 'index'])->name('books.index');

Route::get('/writers/{author_id}/books/create', [BookController::class, 'create'])->name('books.create');

Route::post('/writers/{author_id}/books', [BookController::class, 'store'])->name('books.store');
Route::delete('/writers/{author_id}/books/{id}', [BookController::class, 'destroy'])->name('books.destroy');

Route::get('/writers/{author_id}/books/{id}/edit', [BookController::class, 'edit'])->name('books.edit');
Route::put('/writers/{author_id}/books/{id}', [BookController::class, 'update'])->name('books.update');