<?php

namespace App\Repositories\Eloquents;

use App\Http\Resources\TeacherCollection;
use App\Models\Teacher;
use App\Repositories\Interfaces\TeacherRepositoryInterface;

class TeacherRepository implements TeacherRepositoryInterface {

    function getTeachersData(): array
    {
        $teachers = Teacher::custom()->orderBy('firstname');

        $teachers_no = (clone $teachers)->count();

        $teachers_new = (clone $teachers)->where('created_at', '>=', now()->subWeek());
        $teachers_new_no = $teachers_new->count();

        $teachers_active = (clone $teachers)->where('status', 'active');
        $teachers_active_no = $teachers_active->count();

        $teachers_inactive = (clone $teachers)->where('status', 'inactive');
        $teachers_inactive_no = $teachers_inactive->count();

        if($period = request('period'))
        {
            $teachers_no_growth = growthBetweenTimePeriods((clone $teachers), $period);
            $teachers_new_no_growth = growthBetweenTimePeriods($teachers_new, $period);
            $teachers_active_no_growth = growthBetweenTimePeriods($teachers_active, $period);
            $teachers_inactive_no_growth = growthBetweenTimePeriods($teachers_inactive, $period);

            return array_merge([
                'all' => [
                    'count' => $teachers_no,
                    'growth' => $teachers_no_growth,
                ],
                'new' => [
                    'count' => $teachers_new_no,
                    'growth' => $teachers_new_no_growth,
                ],
                'active' => [
                    'count' => $teachers_active_no,
                    'growth' => $teachers_active_no_growth,
                ],
                'inactive' => [
                    'count' => $teachers_inactive_no,
                    'growth' => $teachers_inactive_no_growth,
                ]
            ], (new TeacherCollection($teachers->paginate(10)))->jsonSerialize());
        }

        return array_merge([
            'all' => $teachers_no,
            'new' => $teachers_new_no,
            'active' => $teachers_active_no,
            'inactive' =>  $teachers_inactive_no,
        ], (new TeacherCollection($teachers->paginate(10)))->jsonSerialize());
    }

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
