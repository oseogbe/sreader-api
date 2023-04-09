<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
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
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'middlename' => $this->middlename,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'profile_pic' => $this->profile_pic,
            'status' => $this->status,
            'school' => SchoolResource::make($this->school),
            'level' => LevelResource::make($this->level),
            'parent' => StudentParentResource::make($this->parent)
        ];
    }
}
