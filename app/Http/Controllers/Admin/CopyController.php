<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadCopyRequest;
use App\Repositories\Interfaces\CopyRepositoryInterface;
use Illuminate\Http\Request;

class CopyController extends Controller
{
    private CopyRepositoryInterface $copyRepository;

    public function __construct(CopyRepositoryInterface $copyRepository)
    {
        $this->copyRepository = $copyRepository;
    }

    public function store(UploadCopyRequest $request)
    {
        $validated = $request->validated();

        $copy = $this->copyRepository->addCopy($validated);

        $level = getLevelNameById($copy['level_id']);

        $subject = getSubjectNameById($copy['subject_id']);

        return response([
            'success' => true,
            'message' => "New copy for $level $subject added",
            'data' => $copy
        ], 201);
    }
}
