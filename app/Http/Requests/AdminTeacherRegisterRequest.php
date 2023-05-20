<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminTeacherRegisterRequest extends FormRequest
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
            'firstname' => ['required', 'string', 'max:50'],
            'lastname' => ['required', 'string', 'max:50'],
            'email' => ['email', 'required', 'max:50', 'unique:teachers'],
            'phone_number' => ['required', 'max_digits:11', 'min_digits:11', 'unique:teachers'],
            'school_id' => ['required', 'exists:schools,id']
        ];
    }

    public function customValidated()
    {
        $validated = parent::validated();

        $validated['password'] = bcrypt('password');

        // $validated['password'] = bcrypt(generateRandomString(8));

        // TODO: send password information as mail to new teacher requesting a password update

        return $validated;
    }
}
