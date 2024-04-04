<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EcritureRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'montant' => 'required',
            'typeEcriture' => 'required',
            'date' => 'required'

        ];
    }
}
