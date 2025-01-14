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
            'proizvod_id' => $this->proizvod_id,
            'korpa_id' => $this->korpa_id,
            'naziv_proizvoda' => $this->proizvod->naziv,  // Pristup proizvodima
            'naziv_korpe' => $this->korpa->status,       // Pretpostavljam da korpa ima status
            'kolicina' => $this->pivot->kolicina,        // Pivot podatak: količina
        ];
    }
}
