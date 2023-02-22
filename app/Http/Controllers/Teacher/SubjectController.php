<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\TeacherRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    private TeacherRepositoryInterface $teacherRepository;

    public function __construct(TeacherRepositoryInterface $teacherRepository)
    {
        $this->teacherRepository = $teacherRepository;
    }

    public function viewMySubjects()
    {
        return response([
            'success' => true,
            'data' => $this->teacherRepository->getTeacherSubjects(Auth::id()),
        ]);
    }

    public function viewSubjectTopics(Request $request)
    {
        $request->validate([
            'level_subject_id' => ['required', 'integer'],
        ]);

        return response([
            'success' => true,
            'data' => $this->teacherRepository->getSubjectTopics($request->level_subject_id),
        ]);
    }

    public function createTopic(Request $request)
    {
        $request->validate([
            'level_subject_id' => ['required', 'integer'],
            'week_id' => ['required', 'integer'],
            'name' => ['required', 'string'],
            'content' => ['required', 'string'],
            'material' => ['nullable', 'string'],
        ]);


    }

    public function updateTopic(int $topic_id)
    {

    }

    public function deleteTopic(int $topic_id)
    {

    }
}
