<?php

namespace App\Http\Controllers\Admin;

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

        if (Auth::guard('admin')->attempt($request->only('email', 'password'), $request->filled('remember_me'))) {

            $admin = $this->adminRepository->getAdminByEmail($validated['email']);

            $token = $this->adminRepository->createAdminAuthToken($admin['id']);

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

    public function user()
    {
        return response([
            'success' => true,
            'user' => $this->adminRepository->getAdminByEmail(auth()->user()->email)
        ]);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response([
            'success' => true,
        ]);
    }
}
