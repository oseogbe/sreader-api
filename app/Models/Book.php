<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class Book extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    protected $casts = [
        'id' => 'string',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->id = mt_rand(1000000000, 9999999999);
        });
    }

    /* @array $appends */
    public $appends = ['cover_size_in_mb', 'cover_url', 'file_size_in_mb', 'file_url'];

    public function level()
    {
        return $this->belongsTo(Level::class)->select('id', 'name');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class)->select('id', 'name');
    }

    public function tests()
    {
        return $this->morphMany(Test::class, 'testable');
    }

    // public function getCoverUrlAttribute()
    // {
    //     return Storage::url($this->cover_path);
    // }

    public function getCoverUrlAttribute()
    {
        return app('firebase.storage')->getBucket()->object($this->cover_path)->signedUrl(time() + 86400);
    }

    public function getCoverSizeInMbAttribute()
    {
        return round($this->cover_size / 1024000, 2);
    }

    // public function getFileUrlAttribute()
    // {
    //     return Storage::url($this->file_path);
    // }

    public function getFileUrlAttribute()
    {
        return app('firebase.storage')->getBucket()->object($this->file_path)->signedUrl(time() + 86400);
    }

    public function getFileSizeInMbAttribute()
    {
        return round($this->file_size / 1024000, 2);
    }

    public function readingProgress($student_id)
    {
        return $this->hasMany(ReadingProgress::class)->where('student_id', $student_id);
    }
}
