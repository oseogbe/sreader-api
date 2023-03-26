<?php

namespace App\Repositories\Eloquents;

use App\Http\Resources\SchoolResource;
use App\Http\Resources\SchoolResourceCollection;
use App\Models\Admin;
use App\Models\School;
use App\Models\SchoolAdmin;
use App\Models\Student;
use App\Models\StudentParent;
use App\Models\Teacher;
use App\Models\Ticket;
use Carbon\Carbon;
use App\Repositories\Interfaces\AdminRepositoryInterface;

class AdminRepository implements AdminRepositoryInterface
{
    function getAdminByEmail(string $email): array
    {
        return Admin::where('email', $email)->firstOrFail()->toArray();
    }

    function getSchoolAdminByEmail(string $email): array
    {
        return SchoolAdmin::with('school')->where('email', $email)->firstOrFail()->toArray();
    }

    function createAdminAuthToken(string $admin_id): array
    {
        $admin = Admin::findOrFail($admin_id);

        $admin->tokens()->delete();

        if($admin->role == 'superadmin')
        {
            return $admin->createToken('sreader-token', ['super-admin'])->toArray();
        }

        return $admin->createToken('sreader-token', ['app-admin'])->toArray();
    }

    function createSchoolAdminAuthToken(string $admin_id): array
    {
        $admin = SchoolAdmin::findOrFail($admin_id);

        $admin->tokens()->delete();

        return $admin->createToken('sreader-token', ['school-admin'])->toArray();
    }

    public function getSchools()
    {
        $schools = School::orderBy('name');

        if($group_by = request()->school_group_by ?? ['unit' => 'month', 'value' => 6]) {
            $joined_at = $group_by['unit'] == 'week' ? Carbon::now()->subWeeks($group_by['value']) : Carbon::now()->subMonths($group_by['value']);
            $schools = $schools->where('created_at', '>=', $joined_at);
        }

        $schools_clone = clone $schools;
        $schools_no = $schools_clone->count();
        $schools_no_growth = getModelPercentageIncrease($schools_clone, ['unit' => $group_by['unit'], 'value' => $group_by['value']]);

        $schools_clone = clone $schools;
        $schools_active = $schools_clone->where('status', 'active');
        $schools_active_no = $schools_active->count();
        $schools_active_no_growth = getModelPercentageIncrease($schools_active, ['unit' => $group_by['unit'], 'value' => $group_by['value']]);

        $schools_clone = clone $schools;
        $schools_inactive = $schools_clone->where('status', 'inactive');
        $schools_inactive_no = $schools_inactive->count();
        $schools_inactive_no_growth = getModelPercentageIncrease($schools_inactive, ['unit' => $group_by['unit'], 'value' => $group_by['value']]);

        return array_merge([
            'revenue' => [
                'count' => 0,
                'growth' => 0,
            ],
            'all' => [
                'count' => $schools_no,
                'growth' => $schools_no_growth,
            ],
            'active' => [
                'count' => $schools_active_no,
                'growth' => $schools_active_no_growth,
            ],
            'inactive' => [
                'count' => $schools_inactive_no,
                'growth' => $schools_inactive_no_growth,
            ]
        ], (new SchoolResourceCollection($schools->paginate(10)))->jsonSerialize());
    }

    public function getDashboardData($filters): array
    {
        return [
            'revenue' => $this->getRevenueSummary(),
            'users' => $this->getUsersSummary(),
            'schools' => $this->getSchoolsSummary(),
            'parents' => $this->getParentsSummary(),
            'revenue_growth' => $this->getRevenueGrowth(),
            'user_growth' => $this->getUserGrowth($filters['user_growth_group_by']),
            'tickets' => $this->getTickets($filters['tickets_group_by']),
            'notifications' => $this->getNotifications()
        ];
    }

    private function getRevenueSummary()
    {
        //

        return [
            'amount' => 0,
            'growth' => 0,
        ];
    }

    private function getUsersSummary()
    {
        $admins = Admin::count();
        $school_admins = SchoolAdmin::count();
        $students = Student::count();
        $teachers = Teacher::count();

        //

        return [
            'count' => $admins + $this->getNoOfParents() + $school_admins + $students + $teachers,
            'growth' => 0,
        ];
    }

    private function getSchoolsSummary()
    {
        //

        return [
            'count' => School::count(),
            'growth' => 0,
        ];
    }

    private function getParentsSummary()
    {
        //

        return [
            'count' => StudentParent::count(),
            'growth' => 0,
        ];
    }

    private function getNoOfSchools()
    {
        return School::count();
    }

    private function getNoOfParents()
    {
        return StudentParent::count();
    }

    private function getRevenueGrowth()
    {
        return 0.00;
    }

    private function getUserGrowth($group_by)
    {
        $joined_at = $group_by['unit'] == 'week' ? Carbon::now()->subWeeks($group_by['value']) : Carbon::now()->subMonths($group_by['value']);

        $studentGrowth = Student::selectRaw('YEAR(created_at) year, MONTH(created_at) month, COUNT(*) count')
                                    ->where('created_at', '>=', $joined_at)
                                    ->groupBy('year', 'month')
                                    ->orderBy('year', 'asc')
                                    ->orderBy('month', 'asc')
                                    ->get();

        $schoolGrowth = School::selectRaw('YEAR(created_at) year, MONTH(created_at) month, COUNT(*) count')
                                    ->where('created_at', '>=', $joined_at)
                                    ->groupBy('year', 'month')
                                    ->orderBy('year', 'asc')
                                    ->orderBy('month', 'asc')
                                    ->get();

        $teacherGrowth = Teacher::selectRaw('YEAR(created_at) year, MONTH(created_at) month, COUNT(*) count')
                                    ->where('created_at', '>=', $joined_at)
                                    ->groupBy('year', 'month')
                                    ->orderBy('year', 'asc')
                                    ->orderBy('month', 'asc')
                                    ->get();

        return [
            'student' => $studentGrowth,
            'school' => $schoolGrowth,
            'teacher' => $teacherGrowth,
        ];
    }

    public function getTickets($group_by)
    {
        $created_at = $group_by['unit'] == 'week' ? Carbon::now()->subWeeks($group_by['value']) : Carbon::now()->subMonths($group_by['value']);

        $tickets = Ticket::where('created_at', '>=', $created_at);

        $ATClone = clone $tickets;
        $newTicketsCount = $ATClone->where('status', 'new')->count();

        $ATClone = clone $tickets;
        $openTicketsCount = $ATClone->where('status', 'open')->count();

        $ATClone = clone $tickets;
        $pendingTicketsCount = $ATClone->where('status', 'pending')->count();

        $ATClone = clone $tickets;
        $resolvedTicketsCount = $ATClone->where('status', 'resolved')->count();

        $ticketsCount = $tickets->count();

        if ($ticketsCount == 0) {
            return [
                'new' => 0,
                'open' => 0,
                'pending' => 0,
                'resolved' => 0,
            ];
        }

        return [
            'new' => round($newTicketsCount / $ticketsCount * 100) ,
            'open' => round($openTicketsCount / $ticketsCount * 100),
            'pending' => round($pendingTicketsCount / $ticketsCount * 100),
            'resolved' => round($resolvedTicketsCount / $ticketsCount * 100),
        ];
    }

    private function getNotifications()
    {
        $notifications = [];

        foreach (auth()->user()->notifications as $key => $notification) {
            switch ($notification->type) {
                case 'App\Notifications\NewTicketNotification':
                    $notifications[$key]['text'] = "You have a new ticket from " . $notification->data['school'] . '.';
                    $notifications[$key]['date'] = Carbon::parse($notification->created_at)->diffForHumans();
                    break;
            }
        }

        return $notifications;
    }

}
