<?php

namespace App\Repositories\Eloquents;

use App\Models\Teacher;
use App\Repositories\Interfaces\TeacherRepositoryInterface;

class TeacherRepository implements TeacherRepositoryInterface {

    function createTeacher(array $teacher_data): array
    {
        return Teacher::create($teacher_data)->toArray();
    }

    function getTeacherByID(string $teacher_id): array
    {
        return Teacher::findOrFail($teacher_id)->toArray();
    }

    function getTeacherByEmail(string $email): array
    {
        return Teacher::where('email', $email)->firstOrFail()->toArray();
    }

    function createTeacherAuthToken(string $teacher_id): array
    {
        $teacher = Teacher::findOrFail($teacher_id);

        $teacher->tokens()->delete();

        return $teacher->createToken('sreader-token', ['teacher'])->toArray();
    }

    function getTeacherSubjects(string $teacher_id): array
    {
        return [];
    }

    function getTeacherSubjectsByLevel(string $teacher_id, int $level_id): array
    {
        return [];
    }

    function getSubjectTopics(int $subject_id): array
    {
        return [];
    }

    function getTopic(int $topic_id): array
    {
        return [];
    }

    function createTopic(int $level_subject_id, int $week_id): array
    {
        return [];
    }

    function updateTopic(int $topic_id): array
    {
        return [];
    }

    function deleteTopic(int $topic_id): bool
    {
        return 0;
    }
}
