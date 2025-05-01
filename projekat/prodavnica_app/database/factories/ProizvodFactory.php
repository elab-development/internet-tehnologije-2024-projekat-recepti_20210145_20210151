<?php

namespace Database\Factories;
use App\Models\Proizvod;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Proizvod>
 */
class ProizvodFactory extends Factory
{
    protected $model = Proizvod::class;

    /**
     * Definiše default stanje za model.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'naziv' => $this->faker->unique()->lexify('Naziv ???'), // Generiše slučajnu reč kao naziv
            'kategorija' => $this->faker->randomElement(['Voce', 'Povrce','Meso' ]), // Slučajna kategorija
            'cena' => $this->faker->randomFloat(2, 10, 1000), // Cena između 10 i 1000 sa 2 decimale
            'dostupna_kolicina' => $this->faker->numberBetween(1, 100), // Broj između 1 i 100
            'tip' => $this->faker->randomElement(['organski', 'neorganski']), // Slučajan tip
        ];
    }
}
