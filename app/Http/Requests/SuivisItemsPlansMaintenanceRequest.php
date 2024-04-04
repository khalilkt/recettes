<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SuivisItemsPlansMaintenanceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'items_plans_maintenance_id' => 'required',
            'ref_etats_avancement_id' => 'required',
        ];
    }
}
