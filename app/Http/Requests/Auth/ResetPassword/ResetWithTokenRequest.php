<?php

namespace App\Http\Requests\Auth\ResetPassword;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class ResetWithTokenRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => ['required', 'string', 'email', 'max:60', 'exists:users'],
            'password' => ['required', 'string', 'min:8', 'max:25', 'confirmed'],
            'token' => 'required|string|min:50|max:100',
        ];
    }

    public function attributes()
    {
        return [
            'email' => 'E-Posta',
            'password' => 'Parola',
            'password_confirmation' => 'Parola (Tekrar)',
        ];
    }
}
