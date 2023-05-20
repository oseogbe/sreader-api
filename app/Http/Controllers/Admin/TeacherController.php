<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminEditTeacherRequest;
use App\Http\Requests\AdminTeacherRegisterRequest;
use App\Repositories\Interfaces\TeacherRepositoryInterface;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    private TeacherRepositoryInterface $teacherRepository;

    public function __construct(TeacherRepositoryInterface $teacherRepository)
    {
        $this->teacherRepository = $teacherRepository;
    }

    public function allTeachers()
    {
        return response([
            'success' => true,
            'data' => $this->teacherRepository->getTeachersData()
        ]);
    }

    public function addTeacher(AdminTeacherRegisterRequest $request)
    {
        $validated = $request->customValidated();

        $teacher = $this->teacherRepository->createTeacher($validated);

        return response([
            'success' => true,
            'message' => 'Teacher registration successful!',
        ], 201);
    }

    public function updateTeacher(AdminEditTeacherRequest $request, string $teacher_id)
    {
        $validated = $request->customValidated();

        $teacher = $this->teacherRepository->updateTeacher($teacher_id, $validated);

        return response([
            'success' => true,
            'message' => 'Teacher details updated!',
        ]);
    }
}
