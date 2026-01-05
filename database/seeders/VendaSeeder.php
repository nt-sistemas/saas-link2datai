<?php

namespace Database\Seeders;

use App\Jobs\CriarVendasJob;
use App\Models\Filial;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class VendaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ano = '2025';
        $tenants = Tenant::query()
            ->whereNot('id', '07636f9a-d75e-466a-a15a-13bba3311c85')
            ->get();

        foreach ($tenants as $tenant) {
            $lojas = Filial::where('tenant_id', $tenant->id)->get();

            foreach ($lojas as $loja) {

                // QUANTIDADE DE MESES NO ANO
                for ($m = 1; $m <= 12; $m++) {

                    $date = Carbon::create($ano, $m);
                    $monthDays = $date->daysInMonth;

                    for ($i = 1; $i <= $monthDays; $i++) {

                        $quantityVendas = rand(50, 200);

                        for ($v = 1; $v < $quantityVendas; $v++) {
                            CriarVendasJob::dispatch($tenant, $loja, $ano, $m, $i);
                        }
                    }
                }

            }
        }
    }
}