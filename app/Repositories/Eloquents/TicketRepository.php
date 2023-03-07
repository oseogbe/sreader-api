<?php

namespace App\Repositories\Eloquents;

use App\Http\Resources\TicketResource;
use App\Http\Resources\TicketResourceCollection;
use App\Models\Ticket;
use App\Repositories\Interfaces\TicketRepositoryInterface;

class TicketRepository implements TicketRepositoryInterface
{
    function getTickets()
    {
        return TicketResource::collection(Ticket::latest()->get());

        // $ticket = Ticket::with('comments')->find('7039596090');

        // foreach($ticket->comments as $key => $comment) {
        //     $ticket->comments[$key]['name'] = 'Regent College';
        // }

        // return $ticket;
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
