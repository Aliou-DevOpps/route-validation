<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ArticleVenteRequest extends FormRequest
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
            'libelle' => 'required|string',
            'prix' => 'required|numeric',
            'qtStock' => 'nullable|integer',
            'statut' => 'required|in:0,1',
            'promo' => 'nullable|integer',
            
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif',
            'reference' => 'required|string',
            'categorie_vente_id' => 'required|exists:categorie_ventes,id',
            'categorie_id' => [
                'required',
                Rule::exists('categories', 'id'), // Vérifiez que la catégorie existe
            ],
            
            'articles' => [
                'required',
                'array',
                'min:3',
                Rule::exists('articles', 'id')->where('categorie_id', $this->input('categorie_id')),
            ],

            'marge' => [
                'required',
                'numeric',
                'between:5000,' . $this->input('prix') / 3, // La marge doit être entre 5000 et 1/3 du prix
            ],
        ];
    }
}
