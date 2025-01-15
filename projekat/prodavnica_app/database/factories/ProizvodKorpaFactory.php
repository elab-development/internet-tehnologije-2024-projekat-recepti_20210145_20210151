<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Korpa;
use App\Models\Proizvod;
use App\Models\ProizvodKorpa;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProizvodKorpa>
 */
class ProizvodKorpaFactory extends Factory
{
    /**
     * Definiše default stanje za model.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'korpa_id' => Korpa::factory(), // Kreira novu korpu ako ne postoji
            'proizvod_id' => Proizvod::factory(), // Kreira novi proizvod ako ne postoji
            'kolicina_proizvoda' => $this->faker->numberBetween(1, 10), // Nasumična količina proizvoda
        ];
    }
}
