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
            'name' => ['required', 'string', 'min:2', 'max:30'],
            'specialty' => ['required'],
        ];
    }
}
