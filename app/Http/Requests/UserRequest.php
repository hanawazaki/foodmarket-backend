<?php

namespace App\Http\Requests;

use App\Actions\Fortify\PasswordValidationRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserRequest extends FormRequest
{
    use PasswordValidationRules;
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
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'max:255', 'string'],
            'email' => ['required|email|max:255|unique:users,email'], 
            'password' => $this->passwordRules(),
            'address' => ['required','string'],
            'roles' => ['required', 'max:255', 'string', 'in:USER,ADMIN'],
            'house_number' => ['required', 'max:255', 'string'],
            'phone_number' => ['required', 'max:255', 'string'],
            'city' => ['required', 'max:255', 'string'],
        ];
    }
}
