<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SuspensionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'mois1' => 'required',
            'mois2' => 'required'
        ];
    }
}
