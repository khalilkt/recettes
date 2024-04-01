<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmplcementRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'libelle' => 'required|max:255|unique:emplacements,libelle,'.$this->id,
            'type' => 'required',
            'localite' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ];
    }
}
