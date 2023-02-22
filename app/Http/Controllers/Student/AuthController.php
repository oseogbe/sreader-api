<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\EmailRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\StudentRegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Mail\ResetPassword;
use App\Repositories\Interfaces\StudentRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    private StudentRepositoryInterface $studentRepository;

    public function __construct(StudentRepositoryInterface $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }

    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember_me'))) {

            $student = $this->studentRepository->getStudentByEmail($validated['email']);

            $token = $this->studentRepository->createStudentAuthToken($student['id']);

            return response([
                'success' => true,
                'message' => 'Welcome back to SReader, ' . $student['firstname'],
                'data' => $student,
                'token' => $token['plainTextToken']
            ]);
        }

        return response([
            'success' => false,
            'message' => 'The provided credentials are incorrect.'
        ], 401);

    }

    public function register(StudentRegisterRequest $request)
    {
        $validated = $request->validated();

        $student = $this->studentRepository->createStudent($validated);

        $token = $this->studentRepository->createStudentAuthToken($student['id']);

        return response([
            'success' => true,
            'message' => 'Welcome to SReader, ' . $student['firstname'],
            'data' => $this->studentRepository->getStudentByID($student['id']),
            'token' => $token['plainTextToken']
        ], 201);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $validated = $request->validated();

        if(!Hash::check($validated['current_password'], Auth::user()->password)) {
            return response([
                'success' => false,
                'message' => 'Current password does NOT match your account information!'
            ], 422);
        }

        $this->studentRepository->updateStudent(Auth::id(), ['password' => $validated['new_password']]);

        return response([
            'success' => true,
            'message' => 'New password enabled!'
        ]);
    }

    public function sendPasswordResetPin(EmailRequest $request)
    {
        $validated = $request->validated();

        $student = $this->studentRepository->getStudentByEmail($validated['email']);

        if ($student) {
            $otp = $this->studentRepository->createResetPasswordOTP($student['id']);

            if ($otp) {
                Mail::to($validated['email'])->send(new ResetPassword($otp));

                return response([
                    'success' => true,
                    'message' => "Please check your email for a 6 digit pin"
                ]);
            }
        } else {
            return response([
                'success' => false,
                'message' => "This email does not exist!"
            ], 400);
        }
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $validated = $request->validated();

        $reset_pin = $this->studentRepository->getResetPasswordOTP($validated['email']);

        if ($reset_pin) {
            $difference = Carbon::now()->diffInSeconds($reset_pin['created_at']);

            if ($difference > 3600) {
                return response([
                    'success' => false,
                    'message' => "Token Expired!"
                ], 400);
            }

            $this->studentRepository->deleteResetPasswordOTP($validated['email']);

        } else {
            return response([
                'success' => false,
                'message' => 'Invalid token!'
            ], 401);
        }

        $student = $this->studentRepository->getStudentByEmail($validated['email']);

        $data = [
            'password' => $request->password,
            // 'remember_token' => Str::random(60),
        ];

        $this->studentRepository->updateStudent($student['id'], $data);

        $token = $this->studentRepository->createStudentAuthToken($student['id']);

        return response([
            'success' => true,
            'message' => 'Your password has been reset',
            'token' => $token['plainTextToken']
        ]);
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();

        return response([
            'success' => true
        ]);
    }
}
