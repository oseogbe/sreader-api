<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTestRequest;
use App\Repositories\Interfaces\AdminRepositoryInterface;
use App\Repositories\Interfaces\TestRepositoryInterface;
use Illuminate\Http\Request;

class TestController extends Controller
{
    private TestRepositoryInterface $testRepository;

    public function __construct(TestRepositoryInterface $testRepository)
    {
        $this->testRepository = $testRepository;
    }

    public function createTest(CreateTestRequest $request)
    {
        $validated = $request->validated();

        $test = $this->testRepository->createTestForBook(
            $validated['testable_id'],
            $validated['term'],
            $validated['week'],
            $validated['type']
        );

        return response([
            'success' => true,
            'data' => $this->testRepository->submitTestQuestions($test['id'], $validated['questions'])
        ], 201);
    }
}
