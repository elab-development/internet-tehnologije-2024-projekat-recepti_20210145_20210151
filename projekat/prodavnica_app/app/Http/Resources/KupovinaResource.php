<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KupovinaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id_kupovine' => $this->id_kupovine,
            'id_user' => $this->id_user,
            'ukupna_cena' => $this->ukupna_cena,
            'nacin_placanja' => $this->nacin_placanja,
            'adresa_dostave' => $this->adresa_dostave,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'proizvodi' => ProizvodKupovinaResource::collection($this->proizvodi), 
        ];
    }
}
