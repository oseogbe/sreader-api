<?php

namespace App\Repositories\Interfaces;

interface TicketRepositoryInterface
{
    public function getTicketGroups(array $group_by);
    public function getTickets();
    public function getTicketGroupsForSchool(array $group_by);
    public function getTicketsForSchool();
    public function getTicket(string $ticket_id);
    public function createTicket(array $ticket_data);
    public function replyTicket(string $ticket_id, string $message);
    public function markTicketAsResolved(string $ticket_id);
}
