<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProizvodKupovinaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'proizvod_id' => $this->proizvod_id,
            'kupovina_id' => $this->kupovina_id,
            'naziv_proizvoda' => $this->proizvod->naziv,  // Pristup proizvodima
            'ukupna_cena' => $this->kupovina->ukupna_cena, // Pristup ukupnoj ceni kupovine
            'kolicina' => $this->pivot->kolicina,         // Pivot podatak: količina
        ];
    }
}
