<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProizvodReceptResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'recept_id' => $this->pivot->recept_id,
            'naziv_recepta' => $this->naziv,
            'kolicina' => $this->pivot->kolicina,
        ];
        
        
    }
}
