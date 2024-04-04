<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExperienceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            //'libelle' => 'required|max:255|unique:famille,libelle,'.$this->id,
            'poste'  => 'required',
            'service' => 'required',
            'annee_deb'  => 'required',
        ];
    }
}
