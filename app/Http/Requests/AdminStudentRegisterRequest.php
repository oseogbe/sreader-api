<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminStudentRegisterRequest extends FormRequest
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
            'middlename' => ['nullable', 'string', 'max:50'],
            'email' => ['email', 'required', 'max:50', 'unique:students'],
            'phone_number' => ['sometimes', 'required', 'max_digits:11', 'min_digits:11', 'unique:students'],
            'school_id' => ['required', 'string', 'exists:schools,id'],
            'parent_id' => ['required', 'string', 'exists:parents,id'],
            'plan' => ['required', 'string'],
            'send_invoice_to_mail' => ['required', 'boolean'],
            'firstname' => Rule::unique('students')->where(function ($query) {
                return $query->where('middlename', request()->middlename)->where('lastname', request()->lastname);
            }),
        ];
    }

    public function messages()
    {
        return [
            'firstname.unique' => "Student is already registered on " . config('app.name'),
        ];
    }

    public function customValidated()
    {
        $validated = parent::validated();

        $validated['password'] = bcrypt('password');

        // $validated['password'] = bcrypt(generateRandomString(8));

        // TODO: send password information as mail to new student requesting a password update

        return $validated;
    }
}
