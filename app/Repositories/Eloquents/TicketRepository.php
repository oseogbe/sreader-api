<?php

namespace App\Repositories\Eloquents;

use App\Http\Resources\TicketResource;
use App\Http\Resources\TicketResourceCollection;
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
        return TicketResource::collection(Ticket::latest()->get());
    }

    function createTicket(array $ticket_data)
    {
        return auth()->user()->tickets()->create($ticket_data);
    }

    function replyTicket(string $ticket_id, string $message)
    {
        $ticket = Ticket::findOrFail($ticket_id);

        return auth()->user()->ticketReplies()->create([
            'ticket_id' => $ticket->id,
            'message' => $message
        ]);
    }
}
