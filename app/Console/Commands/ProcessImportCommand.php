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


            $imports = $tenant->imports()->where('is_processed', false)->chunk(10000, function ($imports) use ($tenant) {

                foreach ($imports as $import) {
                    Log::info("Processando import: {$import->id} - {$import->name}");


                    // Dispara o job para processar o import
                    ProcessImportJob::dispatch($import);


                    $this->info("Import {$import->id} processado com sucesso.");
                }
            });


        }


        // Aqui você pode adicionar a lógica de processamento do import
        // Por exemplo, atualizar o status do import para processado
        //$import->is_processed = true;
        //$import->save();


    }


}


