<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReplyTicketRequest;
use App\Repositories\Interfaces\TicketRepositoryInterface;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    private TicketRepositoryInterface $ticketRepository;

    public function __construct(TicketRepositoryInterface $ticketRepository)
    {
        $this->ticketRepository = $ticketRepository;
    }

    public function all()
    {
        return response([
            'success' => true,
            'data' => $this->ticketRepository->getTickets()
        ]);
    }

    public function open()
    {

    }

    public function reply(ReplyTicketRequest $request, $ticket_id)
    {
        $validated = $request->validated();

        return response([
            'success' => true,
            'data' => $this->ticketRepository->replyTicket($ticket_id, $validated['message'])
        ], 201);
    }
}
