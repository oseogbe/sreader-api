<?php

namespace App\Repositories\Eloquents;

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

        // $admin->tokens()->delete();

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

    private function getUserGrowth($period)
    {
        $joined_at = now()->sub($period);

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

    public function getTickets($period)
    {
        $created_at = now()->sub($period);

        $tickets = Ticket::where('created_at', '>=', $created_at);

        $ticketsCount = (clone $tickets)->count();

        $newTicketsCount = (clone $tickets)->where('status', 'new')->count();

        $openTicketsCount = (clone $tickets)->where('status', 'open')->count();

        $pendingTicketsCount = (clone $tickets)->where('status', 'pending')->count();

        $resolvedTicketsCount = (clone $tickets)->where('status', 'resolved')->count();

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
