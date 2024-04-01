<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourrierRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'titre' =>  'required',
            'ar_origine_id' =>  'required',
            'service_id'  =>  'required',
            'date_transaction'  =>  'required',
            'ref_niveau_importances'  =>  'required',

        ];
    }
}
