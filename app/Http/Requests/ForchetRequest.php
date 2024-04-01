<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ForchetRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'categorie' => 'required',
            'taille' => 'required',
            'emplacement' => 'required',
            'montant' => 'required'

        ];
    }
}
