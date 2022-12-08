<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadBookRequest extends FormRequest
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
            'title' => ['required', 'string', 'unique:books'],
            'cover' => ['required', 'mimes:png,jpg', 'max:2084'],
            'file' => ['required', 'mimes:epub', 'max:20000'],
        ];
    }
}
