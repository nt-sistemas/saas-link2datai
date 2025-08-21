<?php

namespace App\Console\Commands;

use App\Models\PlanoHabilitado;
use Illuminate\Console\Command;

class ProcessGroupsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'etl:process-groups';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tenants = \App\Models\Tenant::all();

        foreach ($tenants as $tenant) {
            $this->info("Processing tenant: {$tenant->name}");

            $groups = \App\Models\Grupo::query()
                ->where('tenant_id', $tenant->id)
                ->where('active', true)
                ->orderBy('order', 'asc')
                ->get();

            if ($groups->isEmpty()) {
                $this->info("No active groups found for tenant: {$tenant->name}");
                continue;
            }

            foreach ($groups as $group) {
                $this->info("Processing group: {$group->name}");

                $grupo_estoque_ids = $group->grupo_estoque()->get()->pluck('id')->toArray();
                $plano_habilitados = $group->plano_habilitados()->get()->pluck('id')->toArray();
                $modalidade_venda_ids = $group->modalidade_venda()->get()->pluck('id')->toArray();


                $vendas = \App\Models\Venda::query()
                    ->when($grupo_estoque_ids, function ($query) use ($grupo_estoque_ids) {
                        return $query->whereIn('grupo_estoque_id', $grupo_estoque_ids);
                    })
                    ->when($plano_habilitados, function ($query) use ($plano_habilitados) {
                        return $query->whereIn('plano_habilitado_id', $plano_habilitados);
                    })
                    ->when($modalidade_venda_ids, function ($query) use ($modalidade_venda_ids) {
                        return $query->whereIn('modalidade_venda_id', $modalidade_venda_ids);
                    })
                    ->where('grupo_id', null)
                    ->where('tenant_id', $tenant->id)
                    ->get();


                foreach ($vendas as $venda) {
                    $venda->grupo_id = $group->id;
                    $venda->save();
                }


                // Here you can add the logic to process each group
                // For example, you might want to update some fields or perform calculations
                // $group->some_field = 'new value';
                // $group->save();

                $this->info("Group processed: {$group->name}");
            }


        }
    }
}
