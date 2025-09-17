<?php

namespace App\Console\Commands;

use App\Jobs\ProcessImportJob;
use App\Models\Filial;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessImportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'etl:process-import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Processa todos os imports pendentes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tenants = \App\Models\Tenant::all();

        foreach ($tenants as $tenant) {

            $this->info("Processando imports para o tenant: {$tenant->name}");


            $imports = $tenant->imports()->where('is_processed', false)->limit(1000)->get();
            foreach ($imports as $import) {
                ProcessImportJob::dispatch($import, $tenant->id);
                $this->info("Import ID {$import->id} despachado para processamento.");
            }
        }


        // Aqui você pode adicionar a lógica de processamento do import
        // Por exemplo, atualizar o status do import para processado
        //$import->is_processed = true;
        //$import->save();


    }
}
