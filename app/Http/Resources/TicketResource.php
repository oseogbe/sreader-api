<?php

namespace App\Http\Resources;

use App\Models\School;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'subject' => $this->subject,
            'message' => $this->message,
            'priority' => $this->priority,
            'status' => $this->status,
            'received' => Carbon::parse($this->created_at)->diffForHumans(),
            'sender' => $this->school ? "school" : "admin",
            'school' => $this->ticketable instanceof School ?
                        $this->ticketable()->select('id', 'name')->get() :
                        $this->receivable()->select('id', 'name')->get(),
            'comments' => TicketCommentResource::collection($this->comments),
        ];
    }
}
