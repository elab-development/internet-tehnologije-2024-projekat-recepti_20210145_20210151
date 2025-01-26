<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProizvodResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'proizvod_id' => $this->id,
            'naziv' => $this->naziv,
            'kategorija' => $this->kategorija,
            'cena' => $this->cena,
            'dostupna_kolicina' => $this->dostupna_kolicina,
            'tip' => $this->tip,
            'recepti' => ProizvodReceptResource::collection($this->recepti), // Vraćanje povezanih recepata
            'kupovine' => ProizvodKupovinaResource::collection($this->kupovine),

        ];
    }
}
