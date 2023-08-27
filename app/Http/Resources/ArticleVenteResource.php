<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ResourceArticle;

class ArticleVenteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'libelle' => $this->libelle,
            'prix' => $this->prix,
            'qtStock' => $this->qtStock,
            'statut' => $this->statut,
            'promo' => $this->promo,
            'marge' => $this->marge,
            'photo' => $this->photo,
            'reference' => $this->reference,
            'categorie_vente_id' => $this->categorie_vente_id,
            'article' => new ResourceArticle($this->article), // Inclure les données de l'article

        ];
    }
}
