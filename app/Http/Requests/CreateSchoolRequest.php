<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSchoolRequest extends FormRequest
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
            'name' => ['required', 'string', 'unique:schools,name'],
            'address' => ['required', 'string'],
            'logo' => ['required', 'mimes:png,jpg,svg', 'max:2084'],
            'pcp' => ['required', 'array'],
            'pcp.name' => ['required', 'string'],
            'pcp.phone_number' => ['required', 'max_digits:11', 'min_digits:11', 'unique:school_admins,phone_number'],
            'pcp.email' => ['required', 'email'],
            'pcp.profile_pic' => ['required', 'mimes:png,jpg', 'max:2084'],
            'scp' => ['required', 'array'],
            'scp.name' => ['required', 'string'],
            'scp.phone_number' => ['required', 'max_digits:11', 'min_digits:11', 'unique:school_admins,phone_number'],
            'scp.email' => ['required', 'email'],
            'scp.profile_pic' => ['required', 'mimes:png,jpg', 'max:2084'],
            'number_of_requests' => ['required', 'numeric'],
            'send_invoice_to_mail' => ['required', 'boolean']
        ];
    }
}
