<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

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

    public function subjectsForLevel($level_id)
    {
        return $this->hasMany(Subject::class)->where('level_id', $level_id);
    }
}
