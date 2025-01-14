<?php

namespace Database\Factories;

use App\Models\Korpa;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Korpa>
 */
class KorpaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Korpa::class;
    
    public function definition(): array
    {
        return [
            'user_id' => User::factory(), // Kreira korisnika za svaku korpu
            'ukupna_cena' => $this->faker->randomFloat(2, 10, 1000), // Generiše cenu između 10 i 1000
            'status' => $this->faker->randomElement(['aktivan', 'kompletan', 'otkazan']), // Status korpe može biti aktivna ili prazna
        ];
    }
}
