<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\AdminRepositoryInterface;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    private AdminRepositoryInterface $adminRepository;

    public function __construct(AdminRepositoryInterface $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    public function allSchools()
    {
        return response([
            'success' => true,
            'data' => $this->adminRepository->getSchools()
        ]);
    }

    // create school admin
    // set pcp to true for first one created
}
