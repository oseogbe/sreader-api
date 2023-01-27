<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Repositories\Interfaces\AdminRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    private AdminRepositoryInterface $adminRepository;

    public function __construct(AdminRepositoryInterface $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        if (Auth::guard('school')->attempt($request->only('email', 'password'), $request->filled('remember_me'))) {

            $admin = $this->adminRepository->getSchoolAdminByEmail($validated['email']);

            $token = $this->adminRepository->createSchoolAdminAuthToken($admin['id']);

            return response([
                'success' => true,
                'message' => 'Welcome back to SReader, ' . $admin['firstname'],
                'data' => $admin,
                'token' => $token['plainTextToken']
            ]);
        }

        return response([
            'success' => false,
            'message' => 'The provided credentials are incorrect.'
        ], 401);

    }
}
