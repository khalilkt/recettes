<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SecteurRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'libelle' => 'required|max:255|unique:secteurs,libelle,'.$this->id,
        ];
    }
}
