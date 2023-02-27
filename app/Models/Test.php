<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    protected $with = ['questions'];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->id = mt_rand(1000000000, 9999999999);
        });
    }

    public function testable()
    {
        return $this->morphTo();
    }

    public function questions()
    {
        return $this->hasMany(TestQuestion::class);
    }

    public function results()
    {
        return $this->hasMany(TestResult::class);
    }
}
