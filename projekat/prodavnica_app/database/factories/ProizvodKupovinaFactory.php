<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Proizvod;
use App\Models\Kupovina;
use App\Models\ProizvodKupovina;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProizvodKupovina>
 */
class ProizvodKupovinaFactory extends Factory
{
    /**
     * Definiše default stanje za model.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'proizvod_id' => Proizvod::factory(), // Kreira novi proizvod ako ne postoji
            'id_kupovine' => Kupovina::factory(), // Kreira novu kupovinu ako ne postoji
            'kolicina' => $this->faker->numberBetween(1, 20), // Nasumična količina proizvoda u kupovini
        ];
    }
}
