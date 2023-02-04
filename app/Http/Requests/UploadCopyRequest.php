<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadCopyRequest extends FormRequest
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
            'subject_id' => ['required', 'string'],
            'level_id' => ['required', 'numeric'],
            'topic' => ['required', 'string'],
            'content' => ['required', 'string'],
            'term' => ['numeric', 'digits_between:1,3'],
            'week' => ['required', 'numeric'],
            'status' => ['required', 'digits_between:1,3'],
        ];
    }
}
