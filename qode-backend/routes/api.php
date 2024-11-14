<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AuthController;
use \App\Http\Controllers\PostController;
use \App\Http\Controllers\CommentController;

Route::get('posts', [PostController::class, 'index']);
Route::get('{post}/comment', [CommentController::class, 'index']);
Route::get('search' , [PostController::class, 'search']);
Route::post('login', [AuthController::class, 'register'])->middleware('throttle:register');
Route::post('verify', [AuthController::class, 'login'])->middleware('throttle:login');
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout' , [AuthController::class, 'logout']);
    Route::group(['prefix' => '/post'] , function () {
        Route::get('/' , [PostController::class, 'index']);
        Route::post('create' , [PostController::class, 'store']);
        Route::post('edit/{post}' , [PostController::class, 'update']);
        Route::post('delete/{post}' , [PostController::class, 'destroy']);
        Route::group(['prefix' => '{post}/comment'] , function () {
            Route::get('/' , [CommentController::class, 'index']);
            Route::post('/create' , [CommentController::class, 'store']);
        });
    });
});
