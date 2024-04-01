<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BudgetRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'libelle' => 'required|max:255|unique:budgets,libelle,'.$this->id,
            'libelle_ar' => 'required',
            'annee' => 'required',
            'ordre' => 'required',

        ];
    }
}
