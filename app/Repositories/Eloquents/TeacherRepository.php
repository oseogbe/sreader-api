<?php

namespace App\Repositories\Eloquents;

use App\Models\Teacher;
use App\Repositories\Interfaces\TeacherRepositoryInterface;

class TeacherRepository implements TeacherRepositoryInterface {

    public function getTeacherByEmail(string $email): array
    {
        return Teacher::where('email', $email)->firstOrFail()->toArray();
    }

    public function createTeacherAuthToken(string $teacher_id): array
    {
        $teacher = Teacher::findOrFail($teacher_id);

        $teacher->tokens()->delete();

        return $teacher->createToken('sreader-token', ['teacher'])->toArray();
    }

    public function getTeacherSubjects(string $teacher_id): array
    {
        return [];
    }

    public function getTeacherSubjectsByLevel(string $teacher_id, int $level_id): array
    {
        return [];
    }

    public function getSubjectTopics(int $subject_id): array
    {
        return [];
    }

    public function getTopic(int $topic_id): array
    {
        return [];
    }

    public function createTopic(int $level_subject_id, int $week_id): array
    {
        return [];
    }

    public function updateTopic(int $topic_id): array
    {
        return [];
    }

    public function deleteTopic(int $topic_id): bool
    {
        return 0;
    }
}
