<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\TestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::group(['middleware' => 'guest'], function () {

    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');
});

Route::group(['middleware' => ['auth:sanctum', 'ability:super-admin,app-admin']], function () {

    Route::prefix('books')->group(function () {

        Route::get('/', [BookController::class, 'allBooks']);
        Route::get('/class', [BookController::class, 'getBooksByClass']);
        Route::post('/', [BookController::class, 'store']);
        Route::delete('/', [BookController::class, 'destroy']);
    });

    Route::prefix('tests')->group(function () {

        Route::post('/', [TestController::class, 'createTest']);
    });
});
