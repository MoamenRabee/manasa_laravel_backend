<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\ConfigController;
use App\Http\Controllers\Api\ExamController;
use App\Http\Controllers\Api\FeedsController;
use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\LessonsController;
use App\Http\Controllers\Api\SystemsController;
use App\Http\Controllers\Api\VideoController;
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
        Route::post('/profile/update', [AuthController::class,'update'])->name('update');
        Route::delete('/profile/delete', [AuthController::class,'delete'])->name('delete');
    })->middleware('auth:api');
});


Route::group([], function () {
    Route::get('/home', [HomeController::class,'index']);
    Route::get('/feeds', [FeedsController::class,'index']);
    Route::get('/system/details', [SystemsController::class, 'show']);
    Route::get('/lesson/details', [LessonsController::class, 'show']);
    Route::get('/video/details', [VideoController::class,'index']);
    Route::get('/exam/{id}/questions', [ExamController::class, 'getQuestions']);
    Route::get('/file/{id}', [FileController::class,'index']);

    Route::post('/lesson/activate', [LessonsController::class, 'activateLesson']);
    Route::post('/system/activate', [SystemsController::class,'activateSystem']);
    Route::post('/code/activate', [HomeController::class,'activateCode']);  
})->middleware('auth:api');


Route::get('/config', [ConfigController::class, 'index']);
