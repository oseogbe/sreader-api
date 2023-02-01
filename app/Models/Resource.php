<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function tests()
    {
        return $this->morphMany(Test::class, 'testable');
    }
}
