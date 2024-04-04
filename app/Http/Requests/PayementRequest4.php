<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PayementRequest4 extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'libelle' => 'required|max:255|unique:payements,libelle,'.$this->id,
            'date' => 'required',

        ];
    }
}
