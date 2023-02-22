<?php

namespace App\Repositories\Eloquents;

use App\Models\Admin;
use App\Models\Book;
use App\Models\School;
use App\Models\SchoolAdmin;
use App\Models\Test;
use App\Repositories\Interfaces\AdminRepositoryInterface;

class AdminRepository implements AdminRepositoryInterface
{
    function getAdminByEmail(string $email): array
    {
        return Admin::where('email', $email)->firstOrFail()->toArray();
    }

    function getSchoolAdminByEmail(string $email): array
    {
        return SchoolAdmin::with('school')->where('email', $email)->firstOrFail()->toArray();
    }

    function createAdminAuthToken(string $admin_id): array
    {
        $admin = Admin::findOrFail($admin_id);

        $admin->tokens()->delete();

        if($admin->role == 'superadmin')
        {
            return $admin->createToken('sreader-token', ['super-admin'])->toArray();
        }

        return $admin->createToken('sreader-token', ['app-admin'])->toArray();
    }

    function createSchoolAdminAuthToken(string $admin_id): array
    {
        $admin = SchoolAdmin::findOrFail($admin_id);

        $admin->tokens()->delete();

        return $admin->createToken('sreader-token', ['school-admin'])->toArray();
    }

    public function getSchools(): array
    {
        $schools = School::orderBy('name')->get(['id', 'name']);
        $schools->each(function($school) {
            $school['pcp'] = $school->admins()->where('is_pcp', true)->select('id', 'firstname', 'lastname', 'email', 'phone_number')->first();
            $school['scp'] = $school->admins()->where('is_pcp', false)->select('id', 'firstname', 'lastname', 'email', 'phone_number')->inRandomOrder()->first();
        });

        return $schools->toArray();
    }
}
