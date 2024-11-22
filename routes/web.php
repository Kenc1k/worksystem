<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HududController;
use App\Http\Controllers\TopshiriqController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Route::get('/', function () {
//     return view('layouts.main');
// });

Route::middleware('auth')->group(function () {
    Route::prefix('admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('category', CategoryController::class);
        Route::resource('hudud', HududController::class);
        Route::resource('topshiriq', TopshiriqController::class);
    });

    Route::get('/my-tasks', [TopshiriqController::class, 'myTasks'])->name('topshiriq.myTasks');
    Route::post('/submit-work/{topshiriq}', [TopshiriqController::class, 'submitWork'])->name('topshiriq.submitWork');
    
});

Route::get('/', [AuthController::class, 'loginPage'])->name('loginPage'); 
Route::post('login', [AuthController::class, 'login'])->name('login'); 
Route::post('logout', [UserController::class, 'logout'])->name('logout');
