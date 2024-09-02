<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::prefix('v1')->group(function () {
    // Route::apiResource('clients', ClientController::class);
    // Route::post('login', [AuthController::class, 'login']);
    // Route::post('register', [UserController::class, 'register']);
    Route::prefix('users')->group(function () {
        Route::post('/', [UserController::class, 'register']);
        Route::get('/', [UserController::class, 'index']);
    });
    Route::middleware('auth:api')->group(function () {
        Route::prefix('articles')->group(function () {
            Route::patch('/{id}',[ArticleController::class,'updateStock']);
            Route::post('/stock',[ArticleController::class,'updateMultipleStocks']);
            Route::get('/',[ArticleController::class,'index']);
            Route::get('/{id}',[ArticleController::class,'show']);
            Route::post('/libelle',[ArticleController::class,'findByLibelle']);
            Route::post('/',[ArticleController::class,'store']);
        });
    });
});
 