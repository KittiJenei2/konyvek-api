<?php

use App\Http\Controllers\UsersController;
use App\Http\Controllers\WriterController;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/users/login', [UsersController::class, 'login']);
Route::get('/users', [UsersController::class, 'index'])->middleware('auth:sanctum');

Route::get('/writers', [WriterController::class, 'index']);

Route::post('/writers', [WriterController::class, 'store'])->middleware('auth:sanctum');;