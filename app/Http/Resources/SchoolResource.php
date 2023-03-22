<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class SchoolResource extends JsonResource
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
            'name' => $this->name,
            'address' => $this->address,
            'logo' => $this->logo ? $this->logoUrl : null,
            'pcp' => new SchoolAdminResource($this->PCP()->first()),
            'scp' => new SchoolAdminResource($this->SCP()->first()),
            'status' => $this->status,
            'date_added' => Carbon::parse($this->created_at)->format('jS F, Y'),
        ];
    }
}
