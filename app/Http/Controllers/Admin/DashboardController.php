<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminDashboardRequest;
use App\Repositories\Interfaces\AdminRepositoryInterface;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private AdminRepositoryInterface $adminRepository;

    public function __construct(AdminRepositoryInterface $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    public function dashboard(AdminDashboardRequest $request)
    {
        $filters = $request->validated();

        return response([
            'success' => true,
            'data'   => $this->adminRepository->getDashboardData($filters),
        ]);
    }

    public function markNotificationsAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return response(['success' => true]);
    }

    public function clearNotifications()
    {
        auth()->user()->notifications()->delete();

        return response(['success' => true]);
    }
}
