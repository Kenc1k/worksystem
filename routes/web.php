<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HududController;
use App\Http\Controllers\TopshiriqController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::middleware('auth')->group(function () {
    // Admin routes
    Route::prefix('admin')->group(function () {
        Route::resource('user', UserController::class);
        Route::resource('category', CategoryController::class);
        Route::resource('hudud', HududController::class);
        Route::resource('topshiriq', TopshiriqController::class);
    });

    Route::get('/my-tasks', [TopshiriqController::class, 'myTasks'])->name('topshiriq.myTasks');
    
});

Route::get('/', [AuthController::class, 'loginPage'])->name('loginPage'); // GET login page
Route::post('login', [AuthController::class, 'login']); // POST login form submission
Route::post('logout', [AuthController::class, 'logout'])->name('logout'); // Logout route




