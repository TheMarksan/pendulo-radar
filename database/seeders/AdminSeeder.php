<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cria ou atualiza admin padrão
        \App\Models\Admin::updateOrCreate(
            [
                'email' => 'admin@admin.com',
            ],
            [
                'name' => 'Administrador',
                'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
                'first_access' => false,
            ]
        );

        // Criar uma rota padrão
        $route = \App\Models\Route::create([
            'name' => 'São Miguel dos Campos',
            'description' => 'Rota padrão',
            'is_active' => true,
        ]);

        // Criar uma chave de acesso padrão
        \App\Models\AccessKey::create([
            'key' => 'ADMIN2025',
            'description' => 'Chave padrão',
            'is_active' => true,
        ]);
    }
