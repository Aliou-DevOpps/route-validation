<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenericValidationRequest extends FormRequest
{
    public function rules()
    {
        return [
            'libelle' => 'required|string|min:3|unique:categories,libelle'
        ];
    }
}
