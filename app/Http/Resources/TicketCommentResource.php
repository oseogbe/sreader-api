<?php

namespace App\Http\Resources;

use App\Models\School;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketCommentResource extends JsonResource
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
            'sender' => $this->ticketable instanceof School ? "school" : "admin",
            'message' => $this->message
        ];
    }
}
