<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            //
            'libelle' => 'required|string|min:3',
            'categorie_id' => 'required|exists:categories,id',
            'photo' => 'required', // Ajout de la règle mimes pour les extensions
            'fournisseurs' => 'array',
            'fournisseurs.*' => 'exists:fournisseurs,id',
            'prix' => 'required|numeric|min:1', // Ajout de la règle min pour le prix
            'quantite' => 'required|numeric|min:1', // Ajout de la règle min pour la quantité
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}
