<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Korpa;
use App\Models\Proizvod;
use App\Models\ProizvodKorpa;

class ProizvodKorpaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kreirajte nekoliko korpi i proizvoda
        $korpe = Korpa::factory()->count(5)->create();
        $proizvodi = Proizvod::factory()->count(20)->create();

        // Popunite pivot tabelu koristeći factory
        foreach ($korpe as $korpa) {
            foreach ($proizvodi->random(rand(3, 7)) as $proizvod) {
                ProizvodKorpa::factory()->create([
                    'korpa_id' => $korpa->id,
                    'proizvod_id' => $proizvod->id,
                ]);
            }
        }
    }
}
