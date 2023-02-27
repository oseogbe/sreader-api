<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\TestRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TestController extends Controller
{
    private TestRepositoryInterface $testRepository;

    public function __construct(TestRepositoryInterface $testRepository)
    {
        $this->testRepository = $testRepository;
    }

    public function processTestResult(Request $request)
    {
        $validated = $request->validate([
            'selected_options' => ['required', 'array'],
            'selected_options.*.question_id' => ['required', 'integer'],
            'selected_options.*.answer' => ['required', 'integer'],
            'test_id' => [
                'required',
                'exists:tests,id',
                Rule::unique('test_results')->where(function ($query) {
                    return $query->where('student_id', Auth::id());
                })
            ]
        ],[
            'test_id.unique' => 'Student has already taken test.'
        ]);

        return response([
            'success' => true,
            'data' => $this->testRepository->processTestResult($validated['test_id'], $validated['selected_options'])
        ]);
    }
}
