<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTicketRequest;
use App\Repositories\Interfaces\TicketRepositoryInterface;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    private TicketRepositoryInterface $ticketRepository;

    public function __construct(TicketRepositoryInterface $ticketRepository)
    {
        $this->ticketRepository = $ticketRepository;
    }

    public function open(CreateTicketRequest $request)
    {
        $validated = $request->validated();

        return response([
            'success' => true,
            'data' => $this->ticketRepository->createTicket($validated)
        ], 201);
    }

    public function reply()
    {

    }

}
