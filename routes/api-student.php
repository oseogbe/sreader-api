<?php

use App\Http\Controllers\Student\AuthController;
use App\Http\Controllers\Student\ActivationController;
use App\Http\Controllers\Student\ProfileController;
use App\Http\Controllers\Student\BookController;
use App\Http\Controllers\Student\TestController;
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

Route::group(['middleware' => 'guest'], function () {

    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');

    Route::post('/register', [AuthController::class, 'register']);

    Route::post('/forgot-password', [AuthController::class, 'sendPasswordResetPin']);

    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
});

Route::group(['middleware' => 'auth:sanctum', 'ability:student'], function () {

    Route::post('/change-password', [AuthController::class, 'changePassword']);

    Route::post('/class', [ProfileController::class, 'selectClass']);

    Route::prefix('books')->group(function () {
        Route::get('/', [BookController::class, 'getBooksByStudentClass']);
        Route::get('/{id}/tests', [BookController::class, 'getBookTests']);
        Route::post('/{id}/last-read', [BookController::class, 'setReadingProgress']);
    });

    Route::prefix('tests')->group(function () {
        Route::post('/process-result', [TestController::class, 'processTestResult']);
    });

    Route::post('/single-activation', [ActivationController::class, 'singleActivation']);

    Route::post('/bulk-activation', [ActivationController::class, 'bulkActivation']);

    Route::post('/logout', [AuthController::class, 'logout']);

});
