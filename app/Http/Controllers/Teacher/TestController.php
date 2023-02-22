<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTestRequest;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function createTest(CreateTestRequest $request)
    {
        $request->validated();

        //
    }
}
