<?php



namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ResourceCategorie extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'libelle' => $this->libelle,
            // Ajoutez d'autres attributs que vous souhaitez exposer
        ];
    }
}
