<?php

use App\Http\Controllers\SchoolAdmin\AuthController;
use App\Http\Controllers\SchoolAdmin\TicketController;
use App\Http\Controllers\SchoolAdmin\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::group(['middleware' => 'guest'], function () {

    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');
});

Route::group(['middleware' => ['auth:sanctum', 'ability:school-admin']], function () {

    Route::get('/admins', [UserController::class, 'viewAdmins']);

    Route::prefix('teachers')->group(function () {

        Route::get('/', [UserController::class, 'viewTeachers']);
        Route::post('/assign-subjects', [UserController::class, 'assignSubjectsToTeacher']);
    });

    Route::prefix('students')->group(function () {

        Route::get('/', [UserController::class, 'viewStudents']);
    });

    Route::prefix('tickets')->group(function () {

        Route::post('/', [TicketController::class, 'open']);
    });

});
