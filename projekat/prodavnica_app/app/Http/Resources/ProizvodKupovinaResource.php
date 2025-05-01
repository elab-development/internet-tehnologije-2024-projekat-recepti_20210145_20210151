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
        return $this->map(function ($item) {
            return [
                'kupovina_id' => $item->pivot->id_kupovine, // ID kupovine iz pivot tabele
                'proizvod_id' => $item->pivot->proizvod_id, // ID proizvoda iz pivot tabele
                'naziv_proizvoda' => $item->naziv, // Naziv proizvoda
                'ukupna_cena' => $item->ukupna_cena, // Ukupna cena kupovine
                'kolicina' => $item->pivot->kolicina, // Kolicina proizvoda iz pivot tabele
            ];
        })->toArray(); 

    }
}
