<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LocaliteRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'libelle' => 'required|max:255|unique:localites,libelle,'.$this->id,
            'libelle_ar' => 'required',
            'commune' => 'required',
            'coordonnees' => 'required',
        ];
    }
}
