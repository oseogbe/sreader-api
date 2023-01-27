<?php

namespace App\Repositories\Interfaces;

interface SchoolRepositoryInterface
{
    public function getAdmins(string $school_id): array;
    public function getTeachers(string $school_id): array;
}
