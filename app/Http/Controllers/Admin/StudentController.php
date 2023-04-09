<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminStudentRegisterRequest;
use App\Repositories\Interfaces\StudentRepositoryInterface;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    private StudentRepositoryInterface $studentRepository;

    public function __construct(StudentRepositoryInterface $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }

    public function allStudents()
    {
        return response([
            'success' => true,
            'data' => $this->studentRepository->getStudentsData()
        ]);
    }

    public function getStudentInfo()
    {
        request()->validate([
            'student_id' => ['required', 'string', 'exists:students,id'],
        ]);

        return response([
            'success' => true,
            'data' => $this->studentRepository->getStudentData(request('student_id'))
        ]);
    }

    public function addStudent(AdminStudentRegisterRequest $request)
    {
        $validated = $request->validated();

        $student = $this->studentRepository->createStudent($validated);

        return response([
            'success' => true,
            'message' => 'Student registration successful!',
            'data' => $this->studentRepository->getStudentByID($student['id']),
        ], 201);
    }
}
