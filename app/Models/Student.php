<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Notifications\ResetPasswordNotification;
use App\Traits\FilterableByDates;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Student extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids, SoftDeletes, FilterableByDates;

    protected $guard = 'student';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'school_id',
        'level_id',
        'firstname',
        'lastname',
        'middlename',
        'email',
        'password',
        'phone_number',
        'profile_pic',
        'status',
        'activated_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'string',
        'email_verified_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->id = mt_rand(1000000000, 9999999999);
        });
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function testResults()
    {
        return $this->hasMany(TestResult::class);
    }

    public function latestTestResults()
    {
        return $this->hasMany(TestResult::class)->latest();
    }

    public function parent()
    {
        return $this->belongsTo(StudentParent::class);
    }

    // calculated as the average of all tests that has been taking by the student in a time period
    public function averagePerformance(string $period)
    {
        $start_of_current_period = now()->sub($period);
        $start_of_prev_period = Carbon::parse($start_of_current_period)->sub($period);

        $current_period_avg = $this->testResults()->where('created_at', '>=', $start_of_current_period)->avg('score');

        $prev_period_avg = $this->testResults()->whereBetween('created_at', [$start_of_prev_period, $start_of_current_period])->avg('score');

        if(!$prev_period_avg) {
            return [
                'percentage' => round($current_period_avg, 2),
                'compared_to_last_period' => 0,
            ];
        }

        return [
            'percentage' => round($current_period_avg, 2),
            'compared_to_last_period' => round(($current_period_avg - $prev_period_avg) / $prev_period_avg * 100, 2),
        ];
    }

    // calculated as the percentage increase or decrease of average performance in a time period
    public function overallGrowth(string $period)
    {
        $start_of_current_period = now()->sub($period);
        $start_of_prev_period = Carbon::parse($start_of_current_period)->sub($period);

        $current_period_avg = $this->testResults()->where('created_at', '>=', $start_of_current_period)->avg('score');

        $prev_period_avg = $this->testResults()->whereBetween('created_at', [$start_of_prev_period, $start_of_current_period])->avg('score');

        if(!$prev_period_avg) {
            return [
                'percentage' => round($current_period_avg, 2),
                'compared_to_last_period' => 0,
            ];
        }

        return [
            'percentage' => round($current_period_avg, 2),
            'compared_to_last_period' => round(($current_period_avg - $prev_period_avg) / $prev_period_avg * 100, 2),
        ];
    }

    public function avgHoursSpentOnAppWeekly(string $period)
    {
        $start_of_current_period = now()->sub($period);
        $avg_hrs_per_week = StudentAppUsage::where('student_id', $this->id)->where('created_at', '>=', $start_of_current_period)->avg('hours');

        $start_of_prev_period = Carbon::parse($start_of_current_period)->sub($period);
        $avg_hrs_per_week_prev_period = StudentAppUsage::where('student_id', $this->id)->whereBetween('created_at', [$start_of_prev_period, $start_of_current_period])->avg('hours');

        if(!$avg_hrs_per_week_prev_period) {
            return [
                'avg_hrs_per_week' => round($avg_hrs_per_week, 1),
                'compared_to_last_period' => 0,
            ];
        }

        return [
            'avg_hrs_per_week' => round($avg_hrs_per_week, 1),
            'compared_to_last_period' => round(($avg_hrs_per_week - $avg_hrs_per_week_prev_period) / $avg_hrs_per_week_prev_period * 100, 1),
        ];
    }
}
