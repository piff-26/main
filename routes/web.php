<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;

Route::get('/', [UserController::class, 'homeView'])->name('user.home');
Route::get('/submit', [UserController::class, 'submitView'])->name('user.submit');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/test', function () {
    return view('user.test');
});

Route::prefix('admin')->group(function(){
    Route::get('/',function(){
        return redirect()->route('admin.dashboard');
    });

    Route::get('login',[AdminController::class,'loginView'])->name('admin.login');
    Route::get('auth/google', [AuthController::class, 'googleAuth'])->name('admin.auth.google');
    Route::get('auth/google/callback', [AuthController::class, 'processLogin'])->name('admin.auth.google.callback');

    
    Route::middleware('admin')->group(function () {
        Route::get('/dashboard',[AdminController::class,'index'])->name('admin.dashboard');
    });
});