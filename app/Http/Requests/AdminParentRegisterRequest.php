<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminParentRegisterRequest extends FormRequest
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
            'email' => ['required', 'email', 'max:50', 'unique:parents'],
            'phone_number' => ['required', 'max_digits:11', 'min_digits:11', 'unique:parents'],
            'profile_pic' => ['sometimes', 'required', 'mimes:png,jpg', 'max:2084'],
        ];
    }

    public function customValidated()
    {
        $validated = parent::validated();

        if ($this->hasFile('profile_pic')) {
            $validated['profile_pic'] = storeFileOnFirebase("parents/profile_pic", $this->file('profile_pic'));
        }

        $validated['password'] = bcrypt('password');

        // $validated['password'] = bcrypt(generateRandomString(8));

        // TODO: send password information as mail to new parent requesting a password update

        return $validated;
    }
}
