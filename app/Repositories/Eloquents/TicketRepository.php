<?php

namespace App\Repositories\Eloquents;

use App\Http\Resources\TicketResource;
use App\Http\Resources\TicketResourceCollection;
use App\Models\Admin;
use App\Models\School;
use App\Models\SchoolAdmin;
use App\Models\Ticket;
use App\Repositories\Interfaces\TicketRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class TicketRepository implements TicketRepositoryInterface
{
    function getTicketGroups($period)
    {
        $created_at = now()->sub($period);

        $tickets = Ticket::where('created_at', '>=', $created_at);

        $ticketsCount = (clone $tickets)->count();
        $openTicketsCount = (clone $tickets)->where('status', 'open')->count();
        $pendingTicketsCount = (clone $tickets)->where('status', 'pending')->count();
        $resolvedTicketsCount = (clone $tickets)->where('status', 'resolved')->count();

        return [
            'all' => $ticketsCount,
            'open' => $openTicketsCount,
            'pending' => $pendingTicketsCount,
            'resolved' => $resolvedTicketsCount,
        ];
    }

    function getTickets()
    {
        return new TicketResourceCollection(Ticket::latest()->get());
    }

    function getTicketGroupsForSchool($period)
    {
        $created_at = now()->sub($period);

        $school = auth()->user()->school;

        $tickets = Ticket::where('created_at', '>=', $created_at)->whereHasMorph(
                            'ticketable',
                            [School::class],
                            function (Builder $query) use ($school) {
                                $query->where('id', $school->id);
                            }
                        )->orWhereHasMorph(
                            'receivable',
                            [School::class],
                            function (Builder $query) use ($school) {
                                $query->where('id', $school->id);
                            }
                        );

        $ticketsCount = (clone $tickets)->count();
        $openTicketsCount = (clone $tickets)->where('status', 'open')->count();
        $pendingTicketsCount = (clone $tickets)->where('status', 'pending')->count();
        $resolvedTicketsCount = (clone $tickets)->where('status', 'resolved')->count();

        return [
            'all' => $ticketsCount,
            'open' => $openTicketsCount,
            'pending' => $pendingTicketsCount,
            'resolved' => $resolvedTicketsCount,
        ];
    }

    function getTicketsForSchool()
    {
        $school = auth()->user()->school;

        $tickets = Ticket::whereHasMorph(
                            'ticketable',
                            [School::class],
                            function (Builder $query) use ($school) {
                                $query->where('id', $school->id);
                            }
                        )->orWhereHasMorph(
                            'receivable',
                            [School::class],
                            function (Builder $query) use ($school) {
                                $query->where('id', $school->id);
                            }
                        )->latest()->get();

        return new TicketResourceCollection($tickets);
    }

    function getTicket(string $ticket_id)
    {
        return new TicketResource(Ticket::findOrFail($ticket_id));
    }

    function createTicket(array $ticket_data)
    {
        $user = auth()->user();

        if ($user instanceof Admin) {
            return $user->tickets()->create($ticket_data);
        }

        if ($user instanceof SchoolAdmin) {
            return $user->school->tickets()->create($ticket_data);
        }
    }

    function replyTicket(string $ticket_id, string $message)
    {
        $ticket = Ticket::findOrFail($ticket_id);

        $user = auth()->user();

        if($this->checkTicketResolved($ticket->status))
        {
            return false;
        }

        if ($user instanceof Admin) {
            $user->ticketReplies()->create([
                'ticket_id' => $ticket->id,
                'message' => $message
            ]);

            $ticket->update(['status' => 'pending']);
        }

        if ($user instanceof SchoolAdmin) {
            $user->school->ticketReplies()->create([
                'ticket_id' => $ticket->id,
                'message' => $message
            ]);

            $ticket->update(['status' => 'open']);
        }

        return true;
    }

    function markTicketAsResolved(string $ticket_id)
    {
        $ticket = Ticket::findOrFail($ticket_id);

        return $ticket->update(['status' => 'resolved']);
    }

    private function checkTicketResolved($status)
    {
        if($status == 'resolved') { return true; }
        return false;
    }
}
