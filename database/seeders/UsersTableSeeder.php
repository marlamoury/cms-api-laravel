<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'João Silva',
            'email' => 'joao@example.com',
            'password' => Hash::make('senha123'),
            'telefone' => '(11) 98765-4321',
            'is_valid' => true
        ]);

        User::create([
            'name' => 'Maria Oliveira',
            'email' => 'maria@example.com',
            'password' => Hash::make('senha123'),
            'telefone' => '(21) 91234-5678',
            'is_valid' => false
        ]);
    }
}
