<?php

namespace App\Repositories\Interfaces;

interface TestRepositoryInterface
{
    public function createTestForBook(string $book_id, int $term, int $week, string $type): array;
    public function submitTestQuestions(int $test_id, array $questions): array;
    public function getTestsForBook(string $book_id);
    public function processTestResult(int $test_id, array $selected_options);
}
