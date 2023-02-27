<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    protected $casts = [
        'id' => 'string'
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->id = mt_rand(1000000000, 9999999999);
        });
    }

    public function admins()
    {
        return $this->hasMany(SchoolAdmin::class);
    }

    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    // public function subjectsForLevel($level_id)
    // {
    //     return $this->hasMany(Subject::class)->where('level_id', $level_id);
    // }
}
