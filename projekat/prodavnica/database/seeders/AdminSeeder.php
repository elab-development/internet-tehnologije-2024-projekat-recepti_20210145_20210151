<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Isidora Jokić',
            'email' => 'ij20210145@student.fon.bg.ac.rs',
            'password' => Hash::make('admin'),
            'uloga' => 'administrator',
        ]);

        User::create([
            'name' => 'Marija Ilic',
            'email' => 'mi20210151@student.fon.bg.ac.rs',
            'password' => Hash::make('admin'),
            'uloga' => 'administrator',
        ]);
    }
}
