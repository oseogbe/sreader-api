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
            'pcp.email' => ['required', 'email', 'unique:school_admins,email'],
            'pcp.profile_pic' => ['required', 'mimes:png,jpg', 'max:2084'],
            'scp' => ['required', 'array'],
            'scp.name' => ['required', 'string'],
            'scp.phone_number' => ['required', 'max_digits:11', 'min_digits:11', 'unique:school_admins,phone_number'],
            'scp.email' => ['required', 'email', 'unique:school_admins,email'],
            'scp.profile_pic' => ['required', 'mimes:png,jpg', 'max:2084'],
            'number_of_requests' => ['required', 'numeric'],
            'send_invoice_to_mail' => ['required', 'boolean']
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
