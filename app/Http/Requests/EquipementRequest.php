<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EquipementRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'libelle' => 'required|max:255|unique:equipements,libelle,'.$this->id,
            'libelle_ar' => 'required',
            'code' => 'required|unique:equipements,code,'.$this->id,
            'localite' => 'required',
            'type' => 'required',
            'secteur' => 'required',


        ];
    }
}
