<?php

use App\Console\Kernel;
use App\Http\Controllers\helpController;
use App\Http\Controllers\postController;
use App\Http\Controllers\tagController;
use App\Http\Controllers\userController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['prefix' => 'user'], function () {
    Route::post('/register', [userController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/verify', [UserController::class, 'verify']);
});
Route::group(['prefix' => 'tag', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/all', [tagController::class, 'index']);
    Route::get('/single/{id}', [tagController::class, 'show']);
    Route::post('/create', [tagController::class, 'create']);
    Route::post('/update/{id}', [tagController::class, 'update']);
    Route::delete('/delete/{id}', [tagController::class, 'destroy']);
});
Route::group(['prefix' => 'post', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/all', [postController::class, 'index']);
    Route::get('/deletedPosts', [postController::class, 'deleted_posts']);
    Route::get('/single/{id}', [postController::class, 'show']);
    Route::post('/create', [postController::class, 'create']);
    Route::post('/update/{id}', [postController::class, 'update']);
    Route::delete('/delete/{id}', [postController::class, 'destroy']);
    Route::get('/restore/{id}', [postController::class, 'restore']);
});


Route::get('/stats', [helpController::class, 'stats']);
