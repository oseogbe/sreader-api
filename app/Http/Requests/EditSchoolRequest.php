<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditSchoolRequest extends FormRequest
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
            'name' => ['sometimes', 'required', 'string', Rule::unique('schools')->ignore(request()->school_id)],
            'address' => ['sometimes', 'required', 'string'],
            'logo' => ['sometimes', 'required', 'mimes:png,jpg,svg'],
            'pcp' => ['sometimes', 'required', 'array'],
            'pcp.id' => ['sometimes', 'required', 'string', 'exists:school_admins,id'],
            'pcp.name' => ['sometimes', 'required', 'string'],
            'pcp.phone_number' => ['sometimes', 'required', 'max_digits:11', 'min_digits:11', Rule::unique('school_admins', 'phone_number')->ignore(request()->input('pcp.id'))],
            'pcp.email' => ['sometimes', 'required', 'email', Rule::unique('school_admins', 'email')->ignore(request()->input('pcp.id'))],
            'pcp.profile_pic' => ['sometimes', 'required', 'mimes:png,jpg', 'max:2084'],
            'scp' => ['sometimes', 'required', 'array'],
            'scp.id' => ['sometimes', 'required', 'string', 'exists:school_admins,id'],
            'scp.name' => ['sometimes', 'required', 'string'],
            'scp.phone_number' => ['sometimes', 'required', 'max_digits:11', 'min_digits:11', Rule::unique('school_admins', 'phone_number')->ignore(request()->input('scp.id'))],
            'scp.email' => ['sometimes', 'required', 'email', Rule::unique('school_admins', 'email')->ignore(request()->input('scp.id'))],
            'scp.profile_pic' => ['sometimes', 'required', 'mimes:png,jpg', 'max:2084'],
            'number_of_requests' => ['sometimes', 'required', 'numeric'],
            'send_invoice_to_mail' => ['sometimes', 'required', 'boolean']
        ];
    }

    public function customValidated()
    {
        $validated = parent::validated();

        if ($this->hasFile('logo')) {
            $validated['logo'] = storeFileOnFirebase("schools/$this->name/logo", $this->file('logo'));
        }

        if (isset($validated['pcp']['name']))
        {
            $pcp_name = getFirstAndLastName($validated['pcp']['name']);
            $validated['pcp']['firstname'] = $pcp_name[0];
            $validated['pcp']['lastname'] = $pcp_name[1];
            unset($validated['pcp']['name']);
        }

        if (isset($validated['scp']['name']))
        {
            $scp_name = getFirstAndLastName($validated['scp']['name']);
            $validated['scp']['firstname'] = $scp_name[0];
            $validated['scp']['lastname'] = $scp_name[1];
            unset($validated['scp']['name']);
        }

        if ($this->hasFile('pcp.profile_pic'))
        {
            $validated['pcp']['profile_pic'] = storeFileOnFirebase("schools/$this->name/admins/profile_pic", $this->file('pcp.profile_pic'));
        }

        if ($this->hasFile('scp.profile_pic'))
        {
            $validated['scp']['profile_pic'] = storeFileOnFirebase("schools/$this->name/admins/profile_pic", $this->file('scp.profile_pic'));
        }

        return $validated;
    }
}
