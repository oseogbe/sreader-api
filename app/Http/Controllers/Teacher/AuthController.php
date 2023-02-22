<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Repositories\Interfaces\TeacherRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    private TeacherRepositoryInterface $teacherRepository;

    public function __construct(TeacherRepositoryInterface $teacherRepository)
    {
        $this->teacherRepository = $teacherRepository;
    }

    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        if (Auth::guard('teacher')->attempt($request->only('email', 'password'), $request->filled('remember_me'))) {

            $teacher = $this->teacherRepository->getTeacherByEmail($validated['email']);

            $token = $this->teacherRepository->createTeacherAuthToken($teacher['id']);

            return response([
                'success' => true,
                'message' => 'Welcome back to SReader, ' . $teacher['firstname'],
                'data' => $teacher,
                'token' => $token['plainTextToken']
            ]);
        }

        return response([
            'success' => false,
            'message' => 'The provided credentials are incorrect.'
        ], 401);
    }
}
