<?php

namespace App\Repositories\Interfaces;

interface ProfileRepositoryInterface
{
    public function selectClass(string $student_id, int $class_id): array;
    public function saveAppUsageTime(string $student_id, float $hours): bool;
}
