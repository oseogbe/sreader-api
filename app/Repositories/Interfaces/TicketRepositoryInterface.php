<?php

namespace App\Repositories\Interfaces;

interface TicketRepositoryInterface
{
    public function getTickets();
    public function createTicket(array $ticket_data);
    public function replyTicket(string $ticket_id, string $message);
}
