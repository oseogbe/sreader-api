<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StudentRegisterRequest extends FormRequest
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
            'level_id' => ['required', 'numeric'],
            'school_id' => ['sometimes', 'required', 'string', 'exists:schools,id'],
            'firstname' => ['required', 'string', 'max:50'],
            'lastname' => ['required', 'string', 'max:50'],
            'middlename' => ['nullable', 'string', 'max:50'],
            'email' => ['email', 'required', 'max:50', 'unique:students'],
            'phone_number' => ['sometimes', 'required', 'max_digits:11', 'min_digits:11', 'unique:students'],
            'password' => ['required', Password::defaults()]
        ];
    }
}
