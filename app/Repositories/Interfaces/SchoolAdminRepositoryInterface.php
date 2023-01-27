<?php

namespace App\Repositories\Interfaces;

interface SchoolAdminRepositoryInterface
{
    public function addSubjectsToTeacher(array $level_subject_ids, string $teacher_id): array;
}
