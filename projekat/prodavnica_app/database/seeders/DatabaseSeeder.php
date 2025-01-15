<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        /*User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);*/
        //$this->call(ProizvodSeeder::class);
        //$this->call(UserSeeder::class);
        //$this->call(ReceptSeeder::class);


        //$this->call(KorpaSeeder::class);
        //$this->call(KupovinaSeeder::class);
        //$this->call(ProizvodSeeder::class);
        //$this->call(ReceptSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ProizvodReceptSeeder::class);
        $this->call(ProizvodKorpaSeeder::class);
        $this->call(ProizvodKupovinaSeeder::class);
        
        
        
        
        
    }
}
