<?php

namespace App\Repositories\Eloquents;

use App\Models\Copy;
use App\Repositories\Interfaces\CopyRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class CopyRepository implements CopyRepositoryInterface
{
    function addCopy(array $copy_data): array
    {
        $copy_data['author'] = Auth::id();

        $copy = Copy::create($copy_data);

        return $copy->toArray();
    }
}
