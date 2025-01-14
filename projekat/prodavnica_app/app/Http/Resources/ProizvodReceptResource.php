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
            'proizvod_id' => $this->pivot->proizvod_id,
            'recept_id' => $this->pivot->recept_id,
            'naziv' => $this->proizvod ? $this->proizvod->naziv : 'N/A',  // Pristup nazivima proizvoda
            'naziv_recepta' => $this->recept ? $this->recept->naziv : 'N/A',      // Pristup nazivima recepata
            'kolicina' => $this->pivot->kolicina,         // Pivot podatak: količina
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
