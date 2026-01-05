<?php

namespace App\Console\Commands;

use App\Jobs\CriarVendasJob;
use App\Models\Filial;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CriarVendasCommand extends Command
{
    public $tenantId;

    public $year;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'link2b:criar-vendas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Criar Vendas de Demonstração';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tenants = Tenant::all();

        $options = $tenants->pluck('name')->toArray();
        $option = $this->choice(
            'Selecione a empresa que deseja Criar Dados?', // Question text
            $options, // Array of options
            0 // Default option index (0 = Option A)
        );

        switch ($option) {
            case 'Todas as Empresas':
                $tenants = Tenant::all();
                break;
            default:
                $tenants = Tenant::where('name', $option)->get();
                $this->info("Iniciando a criação de vendas para a empresa: {$tenants->first()->name}");
                $this->tenantId = $tenants[0]->id;

                $this->yearOptions();
                break;
        }

        return Command::SUCCESS;
    }

    public function yearOptions()
    {
        $option = $this->choice(
            'Selecione o ano para criação de vendas?', // Question text
            ['2023', '2024', '2025', '2026'], // Array of options
            2 // Default option index (0 = Option A)
        );

        switch ($option) {
            case '2023':
                $this->info("Iniciando a criação de vendas para o ano de: {$option}");
                $this->criarVendasParaTenant($this->tenantId, $option);
                break;
            case '2024':
                $this->info("Iniciando a criação de vendas para o ano de: {$option}");

                $this->criarVendasParaTenant($this->tenantId, $option);

                break;
            case '2025':
                $this->info("Iniciando a criação de vendas para o ano de: {$option}");
                $this->criarVendasParaTenant($this->tenantId, $option);

                break;
            case '2026':
                $this->info("Iniciando a criação de vendas para o ano de: {$option}");

                $this->criarVendasParaTenant($this->tenantId, $option);

                break;
        }

        return Command::SUCCESS;
    }

    public function criarVendasParaTenant($tenantId, $year)
    {
        $ano = $year;
        $tenant = Tenant::query()
            ->find($tenantId);

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
