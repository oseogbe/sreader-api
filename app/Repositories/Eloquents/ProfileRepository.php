<?php

namespace App\Repositories\Eloquents;

use App\Models\Student;
use App\Repositories\Interfaces\ProfileRepositoryInterface;

class ProfileRepository implements ProfileRepositoryInterface
{
    function selectClass(string $student_id, int $class_id): array
    {
        $student = Student::with('level')->find($student_id);
        $student->update(['level_id' => $class_id]);
        return $student->toArray();
    }
}
