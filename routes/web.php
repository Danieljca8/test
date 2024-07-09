<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserDataController;

Route::get('/', [UserDataController::class, 'index']);
Route::get('/users', [UserDataController::class, 'index'])->name('users');
Route::post('/update-user', [UserDataController::class, 'updateUser'])->name('updateUser');
