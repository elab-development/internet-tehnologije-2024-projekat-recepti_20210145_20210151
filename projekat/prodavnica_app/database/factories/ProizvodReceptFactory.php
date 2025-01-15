<?php

namespace Database\Factories;

use App\Models\Recept;
use App\Models\Proizvod;
use App\Models\ProizvodRecept;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProizvodRecept>
 */
class ProizvodReceptFactory extends Factory
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
            'recept_id' => Recept::factory(),    // Kreira novi recept ako ne postoji
            'kolicina' => $this->faker->numberBetween(1, 10), // Nasumična količina između 1 i 10
        ];
    }
}
