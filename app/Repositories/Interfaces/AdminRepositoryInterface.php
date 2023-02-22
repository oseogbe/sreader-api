<?php

namespace App\Repositories\Interfaces;

interface AdminRepositoryInterface
{
    public function getAdminByEmail(string $email): array;
    public function getSchoolAdminByEmail(string $email): array;
    public function createAdminAuthToken(string $admin_id): array;
    public function createSchoolAdminAuthToken(string $admin_id): array;
    public function getSchools(): array;
}
