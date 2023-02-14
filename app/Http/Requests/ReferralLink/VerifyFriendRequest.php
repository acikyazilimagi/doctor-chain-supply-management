<?php

namespace App\Http\Requests\ReferralLink;

use Illuminate\Foundation\Http\FormRequest;

class VerifyFriendRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user();
    }

    public function rules()
    {
        return [
            'id' => 'required|integer|min:1'
        ];
    }
}
