<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\ProfileRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    private ProfileRepositoryInterface $profileRepository;

    public function __construct(ProfileRepositoryInterface $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }

    public function selectClass(Request $request)
    {
        $student = $this->profileRepository->selectClass(Auth::id(), $request->class_id);

        return response([
            'success' => true,
            'message' => 'Class selection made',
            'data' => $student,
        ]);
    }
}
