<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ModeleActeItemRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'content_value' => 'required:modeles_actes_items,content_value,'.$this->id,
            'nature' => 'required',
            'content_value_ar' => 'required',
            'ordres' => 'required',
            'position' => 'required',

        ];
    }
}
