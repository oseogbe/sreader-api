<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminEditTeacherRequest extends FormRequest
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

    protected function prepareForValidation(): void
    {
        $this->merge([
            'teacher_id' => request('teacher_id'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'teacher_id' => ['required', 'string', 'exists:teachers,id'],
            'firstname' => ['required', 'string', 'max:50'],
            'lastname' => ['required', 'string', 'max:50'],
            'email' => ['email', 'required', 'max:50', Rule::unique('teachers', 'email')->ignore(request('teacher_id'))],
            'phone_number' => ['required', 'max_digits:11', 'min_digits:11', Rule::unique('teachers', 'phone_number')->ignore(request('teacher_id'))],
            'school_id' => ['required', 'exists:schools,id']
        ];
    }

    public function customValidated()
    {
        $validated = parent::validated();

        unset($validated['teacher_id']);

        return $validated;
    }
}
