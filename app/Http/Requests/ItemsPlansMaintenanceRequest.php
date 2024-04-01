<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemsPlansMaintenanceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'plan_id' => 'required',
            'ref_types_maintenance_id' => 'required',
            'infrastructure_id' => 'required',
        ];
    }
}
