<?php

namespace App\Repositories\Interfaces;

interface SchoolRepositoryInterface
{
    public function getSchoolsData(): array;
    public function getAdmins(string $school_id): array;
    public function getTeachers(string $school_id): array;
    public function createSchool(array $school_data): array;
    public function createSchoolAdmin(array $admin_data): array;
    public function editSchool(string $school_id, array $school_data): array;
    public function editSchoolAdmin(string $admin_id, array $admin_data): array;
}
