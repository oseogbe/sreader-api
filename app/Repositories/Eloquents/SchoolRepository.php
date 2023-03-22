<?php

namespace App\Repositories\Eloquents;

use App\Models\School;
use App\Models\SchoolAdmin;
use App\Models\Teacher;
use App\Repositories\Interfaces\SchoolRepositoryInterface;

class SchoolRepository implements SchoolRepositoryInterface
{
    function getAdmins(string $school_id): array
    {
        return SchoolAdmin::select('id', 'firstname', 'lastname', 'email')->where('school_id', $school_id)->get()->toArray();
    }

    function getTeachers(string $school_id): array
    {
        return Teacher::select('id', 'firstname', 'lastname', 'email')->where('school_id', $school_id)->get()->load('subjects')->toArray();
    }

    function createSchool(array $school_data): array
    {
        return School::create($school_data)->toArray();
    }

    function createSchoolAdmins(array $admins): array
    {
        $school_admins = [];

        foreach ($admins as $admin) {
            $school_admins[] = SchoolAdmin::create($admin);
        }

        return $school_admins;
    }
}
