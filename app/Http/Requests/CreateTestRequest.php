<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateTestRequest extends FormRequest
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
            'testable_id' => ['required', 'string'],
            'teacher_id' => ['nullable', 'string', 'exists:teachers,id'],
            'term' => ['required', 'digits_between:1,3'],
            'week' => ['required', 'numeric'],
            'type' => ['required', 'string', 'in:weekly, standard, speed'],
            'questions' => ['required', 'array'],
            'questions.*.question' => ['required', 'string'],
            'questions.*.image' => ['nullable', 'file', 'mimes:png,jpg'],
            'questions.*.options' => ['required', 'array'],
            'questions.*.options.*' => ['required', 'string'],
            'questions.*.correct_option' => ['required', 'digits_between:0,3'],
            'testable_id' => Rule::unique('tests')->where(function ($query) {
                return $query->where('term', request()->term)->where('week', request()->week)->where('type', request()->type);
            }),
        ];
    }

    public function messages()
    {
        return [
            'testable_id.unique' => 'A test for that book of the same type already exists',
        ];
    }
}
