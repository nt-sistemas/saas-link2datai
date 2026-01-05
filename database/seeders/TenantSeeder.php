<?php

namespace Database\Seeders;

use App\Models\Filial;
use App\Models\GrupoEstoque;
use App\Models\ModalidadeVenda;
use App\Models\PlanoHabilitado;
use App\Models\Tenant;
use App\Models\TipoGrupo;
use App\Models\User;
use App\Models\Vendedor;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $empresaNumber = rand(8, 10);

        $tenant = Tenant::factory()->create([
            'name' => 'Empresa Demo '.$empresaNumber,
            'slug' => 'empresa-demo-'.$empresaNumber,
            'phone' => '(11) 99999-9999',
            'email' => 'empresa@empresademo'.$empresaNumber.'.com.br',
        ]);

        $tipo_grupos = TipoGrupo::where('tenant_id', '07636f9a-d75e-466a-a15a-13bba3311c85')->get();

        $grupo_estoques = GrupoEstoque::where('tenant_id', '07636f9a-d75e-466a-a15a-13bba3311c85')->get();

        $modalidade_vendas = ModalidadeVenda::where('tenant_id', '07636f9a-d75e-466a-a15a-13bba3311c85')->get();

        $plano_habilitados = PlanoHabilitado::where('tenant_id', '07636f9a-d75e-466a-a15a-13bba3311c85')->get();

        foreach ($tipo_grupos as $tipo_grupo) {
            $tenant->tipos()->create([
                'name' => $tipo_grupo->name,
                'description' => $tipo_grupo->description,
            ]);
        }

        foreach ($modalidade_vendas as $modalidade_venda) {
            $tenant->modalidade_vendas()->create([
                'name' => $modalidade_venda->name,
                'description' => $modalidade_venda->description,
            ]);
        }

        foreach ($plano_habilitados as $plano_habilitado) {
            $tenant->plano_habilitados()->create([
                'name' => $plano_habilitado->name,
                'description' => $plano_habilitado->description,
            ]);
        }

        foreach ($grupo_estoques as $grupo_estoque) {
            $tenant->grupo_estoques()->create([
                'name' => $grupo_estoque->name,
                'description' => $grupo_estoque->description,
            ]);
        }

        $quantidadeLojas = rand(10, 50);

        for ($i = 0; $i < $quantidadeLojas; $i++) {
            Filial::create([
                'id' => \Illuminate\Support\Str::uuid()->toString(),
                'name' => 'Loja '.($i + 1),
                'tenant_id' => $tenant->id,
            ]);
        }

        $lojas = Filial::where('tenant_id', $tenant->id)->get();

        $randomVendedor = rand(1, 300);

        foreach ($lojas as $loja) {
            $quantityVendedores = rand(5, 20);

            // CRIAR VENDEDORES
            for ($i = 0; $i < $quantityVendedores; $i++) {
                Vendedor::create([
                    'name' => 'Vendedor '.$randomVendedor++,
                    'filial_id' => $loja->id,
                    'tenant_id' => $tenant->id,
                    'document' => fake()->cpf(),
                ]);
            }

        }

        User::factory()->create(
            [
                'name' => 'Admin '.$tenant->name,
                'email' => 'admin@'.'empresademo'.$empresaNumber.'.com.br',
                'cargo_id' => 1,
                'password' => bcrypt('password'),
                'tenant_id' => $tenant->id,
            ]);

        User::factory()->create(
            [
                'name' => 'Gerente 1 '.$tenant->name,
                'email' => 'gerente1@'.'empresademo'.$empresaNumber.'.com.br',
                'password' => bcrypt('password'),
                'cargo_id' => 2,
                'tenant_id' => $tenant->id,
            ]);

        User::factory()->create(
            [
                'name' => 'Gerente 2 '.$tenant->name,
                'email' => 'gerente2@'.'empresademo'.$empresaNumber.'.com.br',
                'password' => bcrypt('password'),
                'cargo_id' => 2,
                'tenant_id' => $tenant->id,
            ]);

        User::factory()->create(
            [
                'name' => 'Gerente 3 '.$tenant->name,
                'email' => 'gerente3@'.'empresademo'.$empresaNumber.'.com.br',
                'password' => bcrypt('password'),
                'cargo_id' => 2,
                'tenant_id' => $tenant->id,
            ]);

        User::factory()->create(
            [
                'name' => 'Operador 1 '.$tenant->name,
                'email' => 'operador1@'.'empresademo'.$empresaNumber.'.com.br',
                'password' => bcrypt('password'),
                'cargo_id' => 3,
                'tenant_id' => $tenant->id,
            ]);

        User::factory()->create(
            [
                'name' => 'Operador 2 '.$tenant->name,
                'email' => 'operador2@'.'empresademo'.$empresaNumber.'.com.br',
                'password' => bcrypt('password'),
                'cargo_id' => 3,
                'tenant_id' => $tenant->id,
            ]);

        User::factory()->create(
            [
                'name' => 'Operador 3 '.$tenant->name,
                'email' => 'operador3@'.'empresademo'.$empresaNumber.'.com.br',
                'password' => bcrypt('password'),
                'cargo_id' => 3,
                'tenant_id' => $tenant->id,
            ]);

        User::factory()->create(
            [
                'name' => 'Operador 4 '.$tenant->name,
                'email' => 'operador4@'.'empresademo'.$empresaNumber.'.com.br',
                'password' => bcrypt('password'),
                'cargo_id' => 3,
                'tenant_id' => $tenant->id,
            ]);

        User::factory()->create(
            [
                'name' => 'Operador 5 '.$tenant->name,
                'email' => 'operador5@'.'empresademo'.$empresaNumber.'.com.br',
                'password' => bcrypt('password'),
                'cargo_id' => 3,
                'tenant_id' => $tenant->id,
            ]);

    }
}
