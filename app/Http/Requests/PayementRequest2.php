<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PayementRequest2 extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
           'protocol' => 'required',
            'montant' => 'required',
            'typePayement' => 'required',
        ];
    }
}
