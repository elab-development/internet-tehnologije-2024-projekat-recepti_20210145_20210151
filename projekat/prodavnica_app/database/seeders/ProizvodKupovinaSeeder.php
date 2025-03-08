<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Proizvod;
use App\Models\Kupovina;
use App\Models\ProizvodKupovina;

class ProizvodKupovinaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kreiranje nekoliko kupovina i proizvoda
        $proizvodi = Proizvod::factory()->count(20)->create();
        $kupovine = Kupovina::factory()->count(10)->create();

        // Popunjavanje pivot tabele koristeci factory
        foreach ($kupovine as $kupovina) {
            foreach ($proizvodi->random(rand(2, 5)) as $proizvod) {
                ProizvodKupovina::factory()->create([
                    'proizvod_id' => $proizvod->id,
                    'id_kupovine' => $kupovina->id_kupovine,
                    'kolicina' => fake()->numberBetween(1, 10),
                ]);
            }
        }
    }
}
