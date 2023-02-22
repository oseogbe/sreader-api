<?php

use App\Http\Resources\StudentResource;
use App\Models\Level;
use App\Models\Student;
use App\Models\Subject;
use App\Models\TestQuestion;
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

Route::get('/classes', function(Request $request) {
    return Level::select('id', 'name')->get()->toArray();
});

Route::get('/subjects', function(Request $request) {
    return Subject::select('id', 'name')->get()->toArray();
});

