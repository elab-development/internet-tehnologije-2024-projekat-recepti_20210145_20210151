<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Recept;
use App\Models\Proizvod;
use App\Models\ProizvodRecept;

class ProizvodReceptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $recepti = Recept::factory()->count(5)->create();
        $proizvodi = Proizvod::factory()->count(5)->create();

        // Kreiranje pivot podatke koristeci factory
        foreach ($recepti as $recept) {
            foreach ($proizvodi->random(rand(2, 5)) as $proizvod) {
                ProizvodRecept::factory()->create([
                    'proizvod_id' => $proizvod->id,
                    'recept_id' => $recept->id,
                ]);
            }
        }
    }
}

