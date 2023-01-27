<?php

namespace App\Repositories\Eloquents;

use App\Models\School;
use App\Models\SchoolAdmin;
use App\Models\Teacher;
use App\Repositories\Interfaces\SchoolRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SchoolRepository implements SchoolRepositoryInterface
{
    public function getAdmins(string $school_id): array
    {
        return SchoolAdmin::select('id', 'firstname', 'lastname', 'email')->where('school_id', $school_id)->get()->toArray();
    }

    public function getTeachers(string $school_id): array
    {
        return Teacher::select('id', 'firstname', 'lastname', 'email')->where('school_id', $school_id)->get()->load('subjects')->toArray();
    }
}
