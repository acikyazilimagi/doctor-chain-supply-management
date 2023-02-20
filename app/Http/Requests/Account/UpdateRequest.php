<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'first_name' => ['required', 'string', 'min:2', 'max:30'],
            'last_name' => ['required', 'string', 'min:2', 'max:30'],
            'specialty' => ['required'],
        ];
    }

    public function attributes()
    {
        return [
            'first_name' => 'Ad',
            'last_name' => 'Soyad',
            'specialty' => 'Uzmanlık',
        ];
    }
}
