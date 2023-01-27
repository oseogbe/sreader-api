<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelSubject extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'level_subject';

    public function level()
    {
        return $this->belongsTo(Level::class)->select('id', 'name');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class)->select('id', 'name', 'description');
    }
}
