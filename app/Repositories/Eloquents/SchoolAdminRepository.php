<?php

namespace App\Repositories\Eloquents;

use App\Models\Teacher;
use App\Repositories\Interfaces\SchoolAdminRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class SchoolAdminRepository implements SchoolAdminRepositoryInterface
{
    public function addSubjectsToTeacher(array $level_subject_ids, string $teacher_id): array
    {
        $teacher = Teacher::find($teacher_id);

        $school_id = Auth::user()->school->id;

        $teacher->subjects()->syncWithPivotValues($level_subject_ids, ['school_id' => $school_id]);

        return $teacher->subjects->toArray();
    }
}
