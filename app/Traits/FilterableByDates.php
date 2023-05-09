<?php

namespace App\Traits;

trait FilterableByDates
{
    public function scopeCustom($query, $column = 'created_at')
    {
        $query->when(request('period'), function($query, string $period) use ($column) {
            $query->where($column, '>=', now()->sub($period));
        });
    }
}
