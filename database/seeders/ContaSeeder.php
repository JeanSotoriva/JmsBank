<?php

namespace Database\Seeders;

use App\Models\Conta;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Type\Integer;

class ContaSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Conta::create([
            'usuario_id' => 1,
            'numero' => '14777',
            'nome' => 'Jean Sotoriva',
            'saldo' => '2000',
        ]);
    }
}