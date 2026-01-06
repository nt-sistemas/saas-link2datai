<?php

namespace App\Jobs;

use App\Models\Filial;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class TenantSelectedJob implements ShouldQueue
{
    use Queueable;

    public $tenantId;

    public $year;

    /**
     * Create a new job instance.
     */
    public function __construct($tenantId, $year)
    {
        $this->tenantId = $tenantId;
        $this->year = $year;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $ano = $this->year;
        $tenant = Tenant::query()
            ->find($this->tenantId);

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
