<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTicketRequest extends FormRequest
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
            'subject' => ['required', 'string'],
            'message' => ['required', 'string'],
            'priority' => ['required', 'string', 'in:high,medium,low'],
        ];
    }

    public function messages()
    {
        return [
            'priority.in' => 'Priority must be either high, medium or low.',
        ];
    }
}
