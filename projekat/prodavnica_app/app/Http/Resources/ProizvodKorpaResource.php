<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProizvodKorpaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'korpa_id' => $this->pivot->korpa_id,  // ID korpe
            'proizvod_id' => $this->pivot->proizvod_id,  // ID proizvoda
            'naziv_proizvoda' => $this->naziv,  // Naziv proizvoda iz povezane tabele Proizvod
            'cena_proizvoda' => $this->cena,  // Cena proizvoda
            'kolicina_proizvoda' => $this->pivot->kolicina_proizvoda,  // Količina proizvoda u korpi
        ];
    }
}
