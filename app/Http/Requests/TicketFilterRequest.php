<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketFilterRequest extends FormRequest
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
            'tickets_group_by' => ['required', 'array'],
            'tickets_group_by.value' => ['required', 'numeric', 'min:1'],
            'tickets_group_by.unit' => ['required', 'in:week,month'],
        ];
    }
}
