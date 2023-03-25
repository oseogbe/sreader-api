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

    function createSchoolAdmin(array $admin_data): array
    {
        $school_admin = SchoolAdmin::create($admin_data);

        return $school_admin->toArray();
    }

    function editSchool(string $school_id, array $school_data): array
    {
        $school = School::findOrFail($school_id);

        $school->update($school_data);

        return $school->toArray();
    }

    function editSchoolAdmin(string $admin_id, array $admin_data): array
    {
        $school_admin = SchoolAdmin::findOrFail($admin_id);

        $school_admin->update($admin_data);

        return $school_admin->toArray();
    }
}
