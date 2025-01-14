<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Kupovina;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kupovina>
 */
class KupovinaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Kupovina::class;

    public function definition(): array
    {
        return [
            'id_user' => User::factory(), // Kreira korisnika za svaku kupovinu
            'ukupna_cena' => $this->faker->randomFloat(2, 50, 2000), // Generiše ukupnu cenu između 50 i 2000
            'nacin_placanja' => $this->faker->randomElement(['gotovina', 'kartica', 'paypal']), // Nasumičan način plaćanja
            'adresa_dostave' => $this->faker->address, // Nasumična adresa dostave
        ];
    }
}
