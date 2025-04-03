<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\ConfigController;
use App\Http\Controllers\Api\FeedsController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\LessonsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::group([], function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
        Route::put('/profile/update', [AuthController::class,'update'])->name('update');
        Route::delete('/profile/delete', [AuthController::class,'delete'])->name('delete');
    })->middleware('auth:api');
});


Route::group([], function () {
    Route::get('/home', [HomeController::class,'index']);
    Route::get('/feeds', [FeedsController::class,'index']);
    Route::get('/lesson', [LessonsController::class, 'show']);
})->middleware('auth:api');


Route::get('/config', [ConfigController::class, 'index']);
