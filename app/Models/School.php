<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class School extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

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

    public function getLogoUrlAttribute()
    {
        return app('firebase.storage')->getBucket()->object($this->logo)->signedUrl(time() + 86400);
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

    public function PCP()
    {
        return $this->admins()->where('is_pcp', true);
    }

    public function SCP()
    {
        return $this->admins()->where('is_pcp', false)->inRandomOrder()->limit(1);
    }

    public function tickets()
    {
        return $this->morphMany(Ticket::class, 'ticketable');
    }

    public function ticketsReceived()
    {
        return $this->morphMany(Ticket::class, 'receivable');
    }

    public function ticketReplies()
    {
        return $this->morphMany(TicketComment::class, 'ticketable');
    }


}
