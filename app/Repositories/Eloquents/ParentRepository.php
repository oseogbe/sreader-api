<?php

namespace App\Repositories\Eloquents;

use App\Http\Resources\StudentParentCollection;
use App\Http\Resources\StudentParentResource;
use App\Models\StudentParent;
use App\Repositories\Interfaces\ParentRepositoryInterface;
use Carbon\Carbon;

class ParentRepository implements ParentRepositoryInterface
{
    function getParentsData(): array
    {
        $parents = StudentParent::orderBy('firstname');

        if($filter = request()->filter ?? ['unit' => 'month', 'value' => 6]) {
            $joined_at = $filter['unit'] == 'week' ? Carbon::now()->subWeeks($filter['value']) : Carbon::now()->subMonths($filter['value']);
            $parents = $parents->where('created_at', '>=', $joined_at);
        }

        $parents_clone = clone $parents;
        $parents_no = $parents_clone->count();
        $parents_no_growth = getModelPercentageIncrease($parents_clone, ['unit' => $filter['unit'], 'value' => $filter['value']]);

        $parents_clone = clone $parents;
        $parents_new = $parents_clone->where('created_at', '>=', Carbon::now()->subWeek());
        $parents_new_no = $parents_new->count();
        $parents_new_no_growth = getModelPercentageIncrease($parents_new, ['unit' => $filter['unit'], 'value' => $filter['value']]);

        $parents_clone = clone $parents;
        $parents_active = $parents_clone->where('status', 'active');
        $parents_active_no = $parents_active->count();
        $parents_active_no_growth = getModelPercentageIncrease($parents_active, ['unit' => $filter['unit'], 'value' => $filter['value']]);

        $parents_clone = clone $parents;
        $parents_inactive = $parents_clone->where('status', 'inactive');
        $parents_inactive_no = $parents_inactive->count();
        $parents_inactive_no_growth = getModelPercentageIncrease($parents_inactive, ['unit' => $filter['unit'], 'value' => $filter['value']]);

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

    function getParentData(string $parent_id): array
    {
        return StudentParentResource::make(StudentParent::findOrFail($parent_id))->jsonSerialize();
    }

    function createParent(array $parent_data): array
    {
        return [];
    }
}
