<?php

namespace App\Console\Commands;

use App\Jobs\ProcessImportJob;
use App\Models\Filial;
use App\Models\Import;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

use function MongoDB\object;

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

            $this->info("Processando imports para o cliente: {$tenant->name}");

            $imports = $this->getImports($tenant->id);

            if ($imports == 0) {
                $this->info("Nenhum import pendente para o cliente: {$tenant->name}");
                continue;
            }

            $this->info("Encontrados {$imports} imports pendentes para o cliente: {$tenant->name}");

            $dataImports = $tenant->imports()->where('is_processed', false)->limit(500)->get();

            $batchJobs = [];

            foreach ($dataImports as $dataImport) {
                $batchJobs[] = $dataImport;

                if (count($batchJobs) >= 100) {
                    ProcessImportJob::dispatch($tenant->id, $batchJobs)->onQueue('imports_process');
                    $batchJobs = [];
                }
            }

            if (!empty($batchJobs)) {
                ProcessImportJob::dispatch($tenant->id, $batchJobs)->onQueue('imports_process');
            }
        }


        // Aqui você pode adicionar a lógica de processamento do import
        // Por exemplo, atualizar o status do import para processado
        //$import->is_processed = true;
        //$import->save();


    }

    public function getImports($tenantId)
    {
        $imports = Import::where('tenant_id', $tenantId)
            ->where('is_processed', false)
            ->count();

        //$this->info("Encontrados {$imports} imports pendentes para o tenant ID: {$tenantId}");

        return $imports;
    }
}
