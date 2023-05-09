<?php

namespace App\Repositories\Eloquents;

use App\Http\Resources\SchoolResource;
use App\Http\Resources\SchoolResourceCollection;
use App\Models\School;
use App\Models\SchoolAdmin;
use App\Models\Teacher;
use App\Repositories\Interfaces\SchoolRepositoryInterface;

class SchoolRepository implements SchoolRepositoryInterface
{
    function getSchoolsData(): array
    {
        $schools = School::custom()->orderBy('name');

        $schools_no = (clone $schools)->count();

        $schools_active = (clone $schools)->where('status', 'active');
        $schools_active_no = $schools_active->count();

        $schools_inactive = (clone $schools)->where('status', 'inactive');
        $schools_inactive_no = $schools_inactive->count();

        if($period = request('period'))
        {
            $schools_no_growth = growthBetweenTimePeriods((clone $schools), $period);
            $schools_active_no_growth = growthBetweenTimePeriods($schools_active, $period);
            $schools_inactive_no_growth = growthBetweenTimePeriods($schools_inactive, $period);

            return array_merge([
                'revenue' => [
                    'count' => 0,
                    'growth' => 0,
                ],
                'all' => [
                    'count' => $schools_no,
                    'growth' => $schools_no_growth,
                ],
                'active' => [
                    'count' => $schools_active_no,
                    'growth' => $schools_active_no_growth,
                ],
                'inactive' => [
                    'count' => $schools_inactive_no,
                    'growth' => $schools_inactive_no_growth,
                ]
            ], (new SchoolResourceCollection($schools->paginate(10)))->jsonSerialize());
        }

        return array_merge([
            'revenue' => 0,
            'all' => $schools_no,
            'active' => $schools_active_no,
            'inactive' =>  $schools_inactive_no,
        ], (new SchoolResourceCollection($schools->paginate(10)))->jsonSerialize());
    }

    function getSchoolData($school_id): array
    {
        $school = School::find($school_id);

        return (new SchoolResource($school))->jsonSerialize();
    }

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
