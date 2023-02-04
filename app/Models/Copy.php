<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Copy extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function author()
    {
        return $this->belongsTo(Admin::class, 'author', 'id');
    }
}
