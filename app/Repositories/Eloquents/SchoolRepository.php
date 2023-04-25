<?php

namespace App\Repositories\Eloquents;

use App\Http\Resources\SchoolResource;
use App\Http\Resources\SchoolResourceCollection;
use App\Models\School;
use App\Models\SchoolAdmin;
use App\Models\Teacher;
use App\Repositories\Interfaces\SchoolRepositoryInterface;
use Carbon\Carbon;

class SchoolRepository implements SchoolRepositoryInterface
{
    function getSchoolsData(): array
    {
        $schools = School::orderBy('name');

        if($filter = request()->filter ?? ['unit' => 'month', 'value' => 6]) {
            $joined_at = $filter['unit'] == 'week' ? Carbon::now()->subWeeks($filter['value']) : Carbon::now()->subMonths($filter['value']);
            $schools = $schools->where('created_at', '>=', $joined_at);
        }

        $schools_clone = clone $schools;
        $schools_no = $schools_clone->count();
        $schools_no_growth = getModelPercentageIncrease($schools_clone, ['unit' => $filter['unit'], 'value' => $filter['value']]);

        $schools_clone = clone $schools;
        $schools_active = $schools_clone->where('status', 'active');
        $schools_active_no = $schools_active->count();
        $schools_active_no_growth = getModelPercentageIncrease($schools_active, ['unit' => $filter['unit'], 'value' => $filter['value']]);

        $schools_clone = clone $schools;
        $schools_inactive = $schools_clone->where('status', 'inactive');
        $schools_inactive_no = $schools_inactive->count();
        $schools_inactive_no_growth = getModelPercentageIncrease($schools_inactive, ['unit' => $filter['unit'], 'value' => $filter['value']]);

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
