<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Recept;

class ReceptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*$recepti = [
            [
                'naziv' => 'Pita sa jabukama',
                'tip_jela' => 'Desert',
                'vreme_pripreme' => '60',
                'opis_pripreme' => 'Pita sa jabukama je ukusan desert koji se pravi od svežih jabuka, brašna i šećera.',
            ],
            [
                'naziv' => 'Ćufte u sosu',
                'tip_jela' => 'Glavno jelo',
                'vreme_pripreme' => '45',
                'opis_pripreme' => 'Ćufte u sosu su jednostavno jelo od mlevenog mesa, sa začinima, koje se kuva u paradajz sosu.',
            ],
            [
                'naziv' => 'Salata od paradajza',
                'tip_jela' => 'Predjelo',
                'vreme_pripreme' => '10',
                'opis_pripreme' => 'Ova sveža salata od paradajza se priprema sa crnim lukom, maslinovim uljem i začinima.',
            ],
        ];

        // Ubacivanje recepata u bazu
        foreach ($recepti as $recept) {
            Recept::create($recept);
        }

        Recept::factory()->count(50)->create();
        */
    }
}
