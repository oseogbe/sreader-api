<?php

namespace App\Repositories\Interfaces;

interface AdminRepositoryInterface
{
    public function getAdminByEmail(string $email): array;
    public function getSchoolAdminByEmail(string $email): array;
    public function createAdminAuthToken(string $admin_id): array;
    public function createSchoolAdminAuthToken(string $admin_id): array;
    public function createTestForBook(string $book_id, int $term, int $week, string $type): array;
    public function submitTestQuestions(int $test_id, array $questions): array;
}
