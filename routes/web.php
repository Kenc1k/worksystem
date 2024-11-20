<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HududController;
use App\Http\Controllers\TopshiriqController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('layouts.main');
});

Route::resource('users' , UserController::class);
Route::resource('category' , CategoryController::class);
Route::resource('hudud' , HududController::class);
Route::resource('topshiriq' , TopshiriqController::class);