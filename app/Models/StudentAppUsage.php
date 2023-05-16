<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAppUsage extends Model
{
    use HasFactory;

    public $table = 'students_app_usage';

    protected $guarded = [];
}
