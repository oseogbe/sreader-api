<?php

namespace App\Console\Commands;

use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateTicketStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tickets:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Artisan command to update ticket status';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Ticket::all()->each(function ($ticket) {
            if($ticket->status == 'new' && Carbon::parse($ticket->created_at)->diffInDays() >= 7 )
            {
                $ticket->update(['status' => 'open']);
            }
        });
    }
}
