<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePropertyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'city_id' => 'required|exists:cities,id',
            'address_street' => 'required',
            'address_postcode' => 'required',
        ];
    }
}
