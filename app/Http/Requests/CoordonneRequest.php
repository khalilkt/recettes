<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CoordonneRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'latitude' => 'required',
            'longitude' => 'required',
        ];
    }
}
