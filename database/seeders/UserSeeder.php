<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Jean Sotoriva2',
            'email' => 'jeansotoriva2@gmail.com',
            'password' => bcrypt('12345678'),
        ]);
    }
}
