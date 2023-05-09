<?php

namespace App\Traits;

trait FilterableByDates
{
    public function scopeCustom($query, $column = 'created_at')
    {
        $query->when(request('filter'), function($query, $filter) use ($column) {
            $joined_at = $filter['unit'] == 'week' ? now()->subWeeks($filter['value']) : now()->subMonths($filter['value']);
            $query->where($column, '>=', $joined_at);
        });
    }
}
