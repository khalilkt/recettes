<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TypeEquipementRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'libelle' => 'required|max:255|unique:ref_types_equipements,libelle,'.$this->id,
            'libellear' => 'required',
        ];
    }
}
