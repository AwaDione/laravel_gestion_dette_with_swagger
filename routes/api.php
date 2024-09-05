<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});

Route::group(['middleware' => ['auth:api'], 'prefix' => 'v1'], function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::get('/logout', [AuthController::class, 'logout']);
});


Route::group([
    'middleware' => ['auth:api', 'checkRole:2'],
    'prefix' => 'v1'
], function () {
    Route::group(['prefix' => 'clients'], function () {

        Route::get('/', [ClientController::class, 'all']);
        Route::post('/', [ClientController::class, 'store']);
        Route::post('/telephone', [ClientController::class, 'getByTelephone']);
    });
    Route::group(['prefix' => 'articles'], function () {

        Route::patch('/{id}', [ArticleController::class, 'updateStockById']);
        Route::post('/stock', [ArticleController::class, 'updateStock']);
        Route::get('', [ArticleController::class, 'allWithFilterStock']);
        Route::get('/all', [ArticleController::class, 'index']);
        Route::get('/{id}', [ArticleController::class, 'getArticleById']);
        Route::post('/libelle', [ArticleController::class, 'getArticleByLibelle']);
        Route::post('/', [ArticleController::class, 'store']);
    });
});

Route::group([
    'middleware' => ['auth:api', 'checkRole:1'],
    'prefix' => 'v1',
], function () {
    Route::group(['prefix' => 'users'], function () {

        Route::post('/', [UserController::class, 'store']);
        Route::get('/', [UserController::class, 'index']);
    });
});

Route::group([
    'middleware' => ['auth:api', 'checkRole:2,3'],
    'prefix' => 'v1', // Ajout du préfixe global 'v1'
], function () {
    Route::group(['prefix' => 'clients'], function () {

        Route::get('/{id}', [ClientController::class, 'getById']);
        Route::get('/{id}/dettes', [UserController::class, 'listDettesByIdClient']);
        Route::get('/{id}/user', [ClientController::class, 'clientWithUser']);

    });
});
