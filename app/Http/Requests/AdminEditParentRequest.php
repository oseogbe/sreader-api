<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminEditParentRequest extends FormRequest
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
            'parent_id' => request('parent_id'),
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
            'parent_id' => ['required', 'string', 'exists:parents,id'],
            'firstname' => ['required', 'string', 'max:50'],
            'lastname' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:50', Rule::unique('parents', 'email')->ignore(request('parent_id'))],
            'phone_number' => ['required', 'max_digits:11', 'min_digits:11', Rule::unique('parents', 'phone_number')->ignore(request('parent_id'))],
            'profile_pic' => ['sometimes', 'required', 'mimes:png,jpg', 'max:2084'],
        ];
    }

    public function customValidated()
    {
        $validated = parent::validated();

        unset($validated['parent_id']);

        if ($this->hasFile('profile_pic')) {
            $validated['profile_pic'] = storeFileOnFirebase("parents/profile_pic", $this->file('profile_pic'));
        }

        return $validated;
    }
}
