<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTestRequest;
use App\Repositories\Interfaces\AdminRepositoryInterface;
use Illuminate\Http\Request;

class TestController extends Controller
{
    private AdminRepositoryInterface $adminRepository;

    public function __construct(AdminRepositoryInterface $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    public function createTest(CreateTestRequest $request)
    {
        $validated = $request->validated();

        $test = $this->adminRepository->createTestForBook(
            $validated['testable_id'],
            $validated['term'],
            $validated['week'],
            $validated['type']
        );

        return response([
            'success' => true,
            'data' => $this->adminRepository->submitTestQuestions($test['id'], $validated['questions'])
        ], 201);
    }
}
