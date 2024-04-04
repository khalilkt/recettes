<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeRequest extends FormRequest
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
            'nom' =>  'required',
            'prenom' =>  'required',
            'nom_famille' => 'required',
            'ref_genre_id' => 'required',
            'lieu_naissance' => 'required',
            'ref_situation_familliale_id' => 'required',
            'type_contrat' => 'required',
            'date_naissance' => 'required',
            'nni' => 'required|size:10|unique:employes,nni,'.$this->id,
//                         'ref_fonction_id' => 'required',
//                         'ref_appreciations_hierarchie_id' => 'required',

        ];
    }
}
