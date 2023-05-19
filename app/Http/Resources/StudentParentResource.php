<?php

namespace App\Http\Resources;

use App\Models\School;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentParentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $period = $request->period ?? "6 months";

        return [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'profile_pic' => $this->profile_pic,
            'status' => $this->status,
            'no_of_wards' => $this->children()->count(),
            'wards_schools' => $this->children->map(fn ($child) => School::find($child->school_id)->only('id', 'name')),
            'wards' => $this->children->map(function ($child) use ($period) {
                $childInfo = $child->only('id', 'firstname', 'lastname', 'middlename', 'phone_number', 'email', 'status', 'profile_pic');
                $childInfo['overall_growth'] = $child->overallGrowth($period);
                $childInfo['average_performance'] = $child->averagePerformance($period);
                $childInfo['overall_time_spent'] = $child->avgHoursSpentOnAppWeekly($period);
                return $childInfo;
            }),
        ];
    }
}
