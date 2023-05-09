<?php

namespace App\Models;

use App\Traits\FilterableByDates;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class SchoolAdmin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids, FilterableByDates;

    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'id' => 'string'
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->id = mt_rand(1000000000, 9999999999);
        });
    }

    public function getProfilePicUrlAttribute()
    {
        return app('firebase.storage')->getBucket()->object($this->profile_pic)->signedUrl(time() + 86400);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

}
