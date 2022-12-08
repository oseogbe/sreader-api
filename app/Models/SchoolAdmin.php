<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class SchoolAdmin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    protected $guarded = [];
}
