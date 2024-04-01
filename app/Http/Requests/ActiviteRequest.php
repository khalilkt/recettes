<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActiviteRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'libelle' => 'required|max:255|unique:activites,libelle,'.$this->id,
            'libelle_ar' => 'required',
            'categorie' => 'required',

        ];
    }
}
