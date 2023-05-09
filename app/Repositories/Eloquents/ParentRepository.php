<?php

namespace App\Repositories\Eloquents;

use App\Http\Resources\StudentParentCollection;
use App\Http\Resources\StudentParentResource;
use App\Models\StudentParent;
use App\Repositories\Interfaces\ParentRepositoryInterface;

class ParentRepository implements ParentRepositoryInterface
{
    function getParentsData(): array
    {
        $parents = StudentParent::custom()->orderBy('firstname');

        $parents_no = (clone $parents)->count();

        $parents_new = (clone $parents)->where('created_at', '>=', now()->subWeek());
        $parents_new_no = $parents_new->count();

        $parents_active = (clone $parents)->where('status', 'active');
        $parents_active_no = $parents_active->count();

        $parents_inactive = (clone $parents)->where('status', 'inactive');
        $parents_inactive_no = $parents_inactive->count();

        if($period = request('period'))
        {
            $parents_no_growth = growthBetweenTimePeriods((clone $parents), $period);
            $parents_new_no_growth = growthBetweenTimePeriods($parents_new, $period);
            $parents_active_no_growth = growthBetweenTimePeriods($parents_active, $period);
            $parents_inactive_no_growth = growthBetweenTimePeriods($parents_inactive, $period);

            return array_merge([
                'all' => [
                    'count' => $parents_no,
                    'growth' => $parents_no_growth,
                ],
                'new' => [
                    'count' => $parents_new_no,
                    'growth' => $parents_new_no_growth,
                ],
                'active' => [
                    'count' => $parents_active_no,
                    'growth' => $parents_active_no_growth,
                ],
                'inactive' => [
                    'count' => $parents_inactive_no,
                    'growth' => $parents_inactive_no_growth,
                ]
            ], (new StudentParentCollection($parents->paginate(10)))->jsonSerialize());
        }

        return array_merge([
            'all' => $parents_no,
            'new' => $parents_new_no,
            'active' => $parents_active_no,
            'inactive' =>  $parents_inactive_no,
        ], (new StudentParentCollection($parents->paginate(10)))->jsonSerialize());
    }

    function getParentData(string $parent_id): array
    {
        return StudentParentResource::make(StudentParent::findOrFail($parent_id))->jsonSerialize();
    }

    function createParent(array $parent_data): array
    {
        return [];
    }
}
