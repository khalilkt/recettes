<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InfrastructureRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'libelle' => 'required',
            'code' => 'required',
            'libelle_ar'  => 'required',
            'type'  => 'required',
            'etat'  => 'required',
        ];
    }
}
