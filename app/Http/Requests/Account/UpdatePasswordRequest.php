<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'old_password' => ['required', 'string', 'min:8', 'max:25'],
            'password' => ['required', 'string', 'min:8', 'max:25', 'confirmed'],
        ];
    }

    public function attributes()
    {
        return [
            'old_password' => 'Eski Parola',
            'password' => 'Parola',
            'password_confirmation' => 'Parola (Tekrar)',
        ];
    }
}
