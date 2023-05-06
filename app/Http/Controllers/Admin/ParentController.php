<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminParentRegisterRequest;
use App\Repositories\Interfaces\ParentRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ParentController extends Controller
{
    private ParentRepositoryInterface $parentRepository;

    public function __construct(ParentRepositoryInterface $parentRepository)
    {
        $this->parentRepository = $parentRepository;
    }

    public function allParents()
    {
        return response([
            'success' => true,
            'data' => $this->parentRepository->getParentsData()
        ]);
    }

    public function getParentInfo(string $parent_id)
    {
        $validator = Validator::make(
            ['parent_id' => $parent_id],
            ['parent_id' => 'required|exists:parents,id']
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 404);
        }

        return response([
            'success' => true,
            'data' => $this->parentRepository->getParentData($parent_id)
        ]);
    }

    public function addParent(AdminParentRegisterRequest $request)
    {
        $validated = $request->customValidated();

        $parent = $this->parentRepository->createParent($validated);

        return response([
            'success' => true,
            'message' => 'New parent added!',
        ], 201);
    }
}
