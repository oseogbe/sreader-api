<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminDashboardRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'tickets_group_by' => ['required', 'string', 'in:week,month,year'],
            'revenue_group_by' => ['required', 'string', 'in:week,month,year'],
            'user_growth_group_by' => ['required', 'numeric', 'in:6,12']
        ];
    }
}
