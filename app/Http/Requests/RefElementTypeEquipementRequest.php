<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RefElementTypeEquipementRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'libelle' => 'required|max:255|unique:ref_elements,libelle,'.$this->id,
            'libelle_ar' => 'required',
            'type' => 'required',
        ];
    }
}
