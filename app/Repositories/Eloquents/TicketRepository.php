<?php

namespace App\Repositories\Eloquents;

use App\Http\Resources\TicketResource;
use App\Http\Resources\TicketResourceCollection;
use App\Models\Admin;
use App\Models\SchoolAdmin;
use App\Models\Ticket;
use App\Repositories\Interfaces\TicketRepositoryInterface;
use Carbon\Carbon;

class TicketRepository implements TicketRepositoryInterface
{
    function getTicketGroups(array $group_by)
    {
        $created_at = $group_by['unit'] == 'week' ? Carbon::now()->subWeeks($group_by['value']) : Carbon::now()->subMonths($group_by['value']);

        $tickets = Ticket::where('created_at', '>=', $created_at);

        $ATClone = clone $tickets;
        $openTicketsCount = $ATClone->where('status', 'open')->count();

        $ATClone = clone $tickets;
        $pendingTicketsCount = $ATClone->where('status', 'pending')->count();

        $ATClone = clone $tickets;
        $resolvedTicketsCount = $ATClone->where('status', 'resolved')->count();

        $ticketsCount = $tickets->count();

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

    function getTicket(string $ticket_id)
    {
        return new TicketResource(Ticket::findOrFail($ticket_id));
    }

    function createTicket(array $ticket_data)
    {
        return auth()->user()->tickets()->create($ticket_data);
    }

    function replyTicket(string $ticket_id, string $message)
    {
        $ticket = Ticket::findOrFail($ticket_id);

        $user = auth()->user();

        if($this->checkTicketResolved($ticket->status))
        {
            return false;
        }

        $user->ticketReplies()->create([
            'ticket_id' => $ticket->id,
            'message' => $message
        ]);

        if ($user instanceof Admin) {
            $ticket->update(['status' => 'pending']);
        }

        if ($user instanceof SchoolAdmin) {
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
