<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:30'],
            'email' => ['required', 'string', 'email', 'max:60', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'max:25', 'confirmed'],
            'legal_text' => ['accepted'],
            'kvkk_text' => ['accepted'],
            'specialty' => ['required', 'min:1'],
            'referral_code' => ['required', 'min:16', 'max:16'],
        ];
    }
}
