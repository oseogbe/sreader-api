<?php

namespace App\Repositories\Eloquents;

use App\Models\Student;
use App\Models\StudentAppUsage;
use App\Repositories\Interfaces\ProfileRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ProfileRepository implements ProfileRepositoryInterface
{
    function selectClass(string $student_id, int $class_id): array
    {
        $student = Student::with('level')->find($student_id);
        $student->update(['level_id' => $class_id]);
        return $student->toArray();
    }

    function saveAppUsageTime(string $student_id, float $hours): bool
    {
        $weeklyUsage = StudentAppUsage::updateOrCreate(
                                ['student_id' => $student_id, 'startdate' => now()->startOfWeek(), 'enddate' => now()->endOfWeek()],
                                ['hours' => DB::raw('hours + ' . $hours)],
                            );

        return (bool) $weeklyUsage;
    }
}
