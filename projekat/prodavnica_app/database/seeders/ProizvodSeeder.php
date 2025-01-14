<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Proizvod;

class ProizvodSeeder extends Seeder
{
    public function run(): void
    {
        $proizvodi = [
            [
                'naziv' => 'Borovnica',
                'kategorija' => 'Voce',
                'cena' => 120.50,
                'dostupna_kolicina' => 100,
                'tip' => 'organski',
            ],
            [
                'naziv' => 'Krastavac',
                'kategorija' => 'Povrce',
                'cena' => 80.00,
                'dostupna_kolicina' => 200,
                'tip' => 'neorganski',
            ],
            [
                'naziv' => 'Kruska',
                'kategorija' => 'Voce',
                'cena' => 300.00,
                'dostupna_kolicina' => 50,
                'tip' => 'organski',
            ],
        ];

        foreach ($proizvodi as $proizvod) {
            Proizvod::create($proizvod);
        }
    }
}
