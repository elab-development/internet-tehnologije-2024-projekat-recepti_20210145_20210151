<?php

namespace Database\Factories;
use App\Models\Recept;
use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recept>
 */
class ReceptFactory extends Factory
{
    protected $model = Recept::class;

    /**
     * Definiše default stanje za model.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'naziv' => $this->faker->sentence(3), // Nasumičan naziv sa 3 reči
            'tip_jela' => $this->faker->randomElement(['Predjelo', 'Glavno jelo', 'Desert', 'Salata']), // Slučajan tip jela
            'vreme_pripreme' => $this->faker->numberBetween(10, 120), // Vreme pripreme između 10 i 120 minuta
            'opis_pripreme' => $this->faker->paragraph(5), // Opis pripreme sa 5 rečenica
        ];
    }
}
