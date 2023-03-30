<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
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

    public function comments()
    {
        return $this->hasMany(TicketComment::class);
    }

    public function ticketable()
    {
        return $this->morphTo();
    }

    public function receivable()
    {
        return $this->morphTo();
    }
}
