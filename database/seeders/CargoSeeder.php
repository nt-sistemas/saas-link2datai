<?php

namespace Database\Seeders;

use App\Models\Cargo;
use Illuminate\Database\Seeder;

class CargoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Cargo::create([
            'name' => 'Administrador',
            'description' => 'Usuário com privilégios administrativos.',
        ]);
        Cargo::create([
            'name' => 'Gerente',
            'description' => 'Usuário responsável pela gestão de equipes e projetos.',
        ]);
        Cargo::create([
            'name' => 'Funcionário',
            'description' => 'Usuário padrão com acesso limitado às funcionalidades do sistema.',
        ]);

    }
}
