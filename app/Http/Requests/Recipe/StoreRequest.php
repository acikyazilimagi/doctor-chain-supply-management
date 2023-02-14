<?php

namespace App\Http\Requests\Recipe;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user();
    }

    public function rules()
    {
        return [
            'title' => 'required|min:2|max:200',
            'description' => 'sometimes|max:2500',

            'items.*.text' => 'required|min:2|max:100',
            'items.*.count' => 'required|integer|min:1|max:1000000',
            'items.*.category_id' => 'required|integer|min:1|max:25',

            'address' => 'array:city,district,neighbourhood,address_detail',
            'address.city' => 'required|integer|min:1|max:100',
            'address.district' => 'required|string|min:2|max:25',
            'address.neighbourhood' => 'required|integer|min:1|max:100000',
            'address.address_detail' => 'sometimes|string|max:500',
        ];
    }
}
