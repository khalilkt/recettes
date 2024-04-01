<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlansMaintenanceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'libelle' => 'required|max:255|unique:plans_maintenances,libelle,'.$this->id,

        ];
    }
}
