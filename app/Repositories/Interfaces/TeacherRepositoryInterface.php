<?php

namespace App\Repositories\Interfaces;

interface TeacherRepositoryInterface
{
    public function getTeachersData(): array;
    public function createTeacher(array $teacher_data): array;
    public function updateTeacher(string $teacher_id, array $data): bool;
    public function getTeacherByID(string $teacher_id): array;
    public function getTeacherByEmail(string $email): array;
    public function createTeacherAuthToken(string $teacher_id): array;
    public function getTeacherSubjects(string $teacher_id): array;
    public function getTeacherSubjectsByLevel(string $teacher_id, int $level_id): array;
    public function getSubjectTopics(int $level_subject_id): array;
    public function getTopic(int $topic_id): array;
    public function createTopic(int $level_subject_id, int $week_id): array;
    public function updateTopic(int $topic_id): array;
    public function deleteTopic(int $topic_id): bool;
}
