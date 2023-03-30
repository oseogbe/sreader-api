<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminTicketRequest extends FormRequest
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
            'school_id' => ['required', 'string', 'exists:schools,id'],
            'subject'   => ['required', 'string'],
            'message'   => ['required', 'string'],
            'priority'  => ['required', 'string', 'in:high,medium,low'],
        ];
    }

    public function messages()
    {
        return [
            'priority.in' => 'Priority must be either high, medium or low.',
        ];
    }

    public function customValidated()
    {
        $validated = parent::validated();

        $validated['receivable_type'] = 'App\Models\School';
        $validated['receivable_id'] = $validated['school_id'];

        unset($validated['school_id']);

        return $validated;
    }
}
