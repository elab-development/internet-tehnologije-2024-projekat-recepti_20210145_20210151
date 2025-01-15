<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KorpaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'proizvodi' => ProizvodKorpaResource::collection($this->proizvodi), // Povezani proizvodi putem ProizvodKorpa
            'ukupna_cena' => $this->ukupna_cena, // Ukupna cena u korpi
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
