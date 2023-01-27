<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\SchoolAdminRepositoryInterface;
use App\Repositories\Interfaces\SchoolRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private SchoolRepositoryInterface $schoolRepository;
    private SchoolAdminRepositoryInterface $schoolAdminRepository;

    public function __construct(SchoolRepositoryInterface $schoolRepository, SchoolAdminRepositoryInterface $schoolAdminRepository)
    {
        $this->schoolRepository = $schoolRepository;
        $this->schoolAdminRepository = $schoolAdminRepository;
    }

    public function viewAdmins()
    {
        $school_id = Auth::user()->school->id;

        return response([
            'success' => true,
            'data' => $this->schoolRepository->getAdmins($school_id),
        ]);
    }

    public function viewTeachers()
    {
        $school_id = Auth::user()->school->id;

        return response([
            'success' => true,
            'data' => $this->schoolRepository->getTeachers($school_id)
        ]);
    }

    public function assignSubjectsToTeacher(Request $request)
    {
        $request->validate([
            'level_subject_ids' => ['required', 'array'],
            'level_subject_ids.*' => ['integer'],
            'teacher_id' => ['required', 'string']
        ]);

        return response([
            'success' => true,
            'data' => $this->schoolAdminRepository->addSubjectsToTeacher($request->level_subject_ids, $request->teacher_id)
        ]);
    }

    public function viewStudents()
    {
        return response([
            'success' => true,
            'data' => []
        ]);
    }
}
