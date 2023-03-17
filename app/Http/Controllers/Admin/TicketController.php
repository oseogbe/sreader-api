<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReplyTicketRequest;
use App\Http\Requests\TicketFilterRequest;
use App\Repositories\Interfaces\TicketRepositoryInterface;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    private TicketRepositoryInterface $ticketRepository;

    public function __construct(TicketRepositoryInterface $ticketRepository)
    {
        $this->ticketRepository = $ticketRepository;
    }

    public function all(TicketFilterRequest $request)
    {
        $filter = $request->validated();

        return response([
            'success' => true,
            'data' => [
                'ticket_groups' => $this->ticketRepository->getTicketGroups($filter['tickets_group_by']),
                'tickets' => $this->ticketRepository->getTickets()
            ]
        ]);
    }

    public function single(string $ticket_id)
    {
        return response([
            'success' => true,
            'data' => $this->ticketRepository->getTicket($ticket_id)
        ]);
    }

    public function open()
    {

    }

    public function reply(ReplyTicketRequest $request, string $ticket_id)
    {
        $validated = $request->validated();

        if($this->ticketRepository->replyTicket($ticket_id, $validated['message'])) {
            return response([
                'success' => true,
                'message' => 'Ticket replied'
            ], 201);
        }

        return response([
            'success' => false,
            'message' => 'Ticket has already been marked as resolved.'
        ], 409);
    }

    public function markAsResolved(string $ticket_id)
    {
        $this->ticketRepository->markTicketAsResolved($ticket_id);

        return response([
            'success' => true,
            'message' => 'Ticket marked as resolved'
        ]);
    }
}
