<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Marko Markovic',
                'email' => 'marko@example.com',
                'password' => Hash::make('lozinka123'), // Ne zaboravite da hasirate lozinku
                'uloga' => 'gost',
            ],
            [
                'name' => 'Ana Anic',
                'email' => 'ana@example.com',
                'password' => Hash::make('lozinka456'),
                'uloga' => 'korisnik',
            ],
            [
                'name' => 'Nikola Nikolic',
                'email' => 'nikola@example.com',
                'password' => Hash::make('lozinka789'),
                'uloga' => 'gost',
            ],
        ];

        // Ubacivanje korisnika u bazu
        foreach ($users as $user) {
            User::create($user);
        }
    }
}
