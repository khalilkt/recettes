<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ModeleActeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'libelle' => 'required|max:255|unique:modeles_actes,libelle,'.$this->id,
            'libelle_ar' => 'required|max:255|unique:modeles_actes,libelle_ar,'.$this->id,
            'titre' => 'required|max:255|unique:modeles_actes,titre,'.$this->id,
            'titre_ar' => 'required|max:255|unique:modeles_actes,titre_ar,'.$this->id,
        ];
    }
}
