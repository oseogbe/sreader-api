<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\ProfileRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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

    public function saveAppUsageTime(Request $request, int $student_id)
    {
        $validator = Validator::make(
            ['student_id' => $student_id],
            ['student_id' => 'required|exists:students,id'],
        );

        $validator->after(function ($validator) use ($request) {
            if (!is_numeric($request->hours_spent)) {
              $validator->errors()->add(
                'hours_spent',
                'Invalid hours spent value'
              );
            }
        });

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $this->profileRepository->saveAppUsageTime($student_id, $request->hours_spent);

        return response([
            'success' => true,
        ]);
    }
}
