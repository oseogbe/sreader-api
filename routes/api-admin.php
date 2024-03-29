<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\CopyController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ParentController;
use App\Http\Controllers\Admin\SchoolController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\TestController;
use App\Http\Controllers\Admin\TicketController;
use Illuminate\Support\Facades\Route;



Route::group(['middleware' => 'guest'], function () {

    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');
});

Route::group(['middleware' => ['auth:sanctum', 'ability:super-admin,app-admin']], function () {

    Route::get('/user', [AuthController::class, 'user']);

    Route::get('/dashboard', [DashboardController::class, 'dashboard']);

    Route::get('/notifications/mark-as-read', [DashboardController::class, 'markNotificationsAsRead']);
    Route::get('/notifications/clear', [DashboardController::class, 'clearNotifications']);

    Route::prefix('schools')->group(function () {

        Route::get('/', [SchoolController::class, 'allSchools']);
        Route::get('/{school_id}', [SchoolController::class, 'getSchool']);
        Route::post('/', [SchoolController::class, 'addSchool']);
        Route::patch('/{school_id}', [SchoolController::class, 'updateSchool']);
    });

    Route::prefix('students')->group(function () {

        Route::get('/', [StudentController::class, 'allStudents']);
        Route::get('/{student_id}', [StudentController::class, 'getStudentInfo']);
        Route::post('/', [StudentController::class, 'addStudent']);
        // Route::patch('/{student_id}', [StudentController::class, 'updateStudent']);
    });

    Route::prefix('parents')->group(function () {

        Route::get('/', [ParentController::class, 'allParents']);
        Route::get('/{parent_id}', [ParentController::class, 'getParentInfo']);
        Route::post('/', [ParentController::class, 'addParent']);
        Route::patch('/{parent_id}', [ParentController::class, 'updateParent']);
    });

    Route::prefix('teachers')->group(function () {

        Route::get('/', [TeacherController::class, 'allTeachers']);
        Route::post('/', [TeacherController::class, 'addTeacher']);
        Route::patch('/{teacher_id}', [TeacherController::class, 'updateTeacher']);
    });

    Route::prefix('books')->group(function () {

        Route::get('/', [BookController::class, 'allBooks']);
        Route::get('/class', [BookController::class, 'getBooksByClass']);
        Route::post('/', [BookController::class, 'store']);
        Route::delete('/', [BookController::class, 'destroy']);
        Route::get('/{id}/tests', [BookController::class, 'getBookTests']);
    });

    Route::prefix('copies')->group(function () {

        Route::post('/', [CopyController::class, 'store']);
    });

    Route::prefix('tests')->group(function () {

        Route::post('/', [TestController::class, 'createTest']);
    });

    Route::prefix('tickets')->group(function () {
        Route::get('/', [TicketController::class, 'all']);
        Route::get('/{ticket_id}', [TicketController::class, 'single']);
        Route::post('/open', [TicketController::class, 'open']);
        Route::post('/{ticket_id}/reply', [TicketController::class, 'reply']);
        Route::post('/{ticket_id}/mark-as-resolved', [TicketController::class, 'markAsResolved']);
    });

    Route::post('logout', [AuthController::class, 'logout']);
});
