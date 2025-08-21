<?php

namespace App\Console\Commands;

use App\Models\Import;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class MigrateDataToMongoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'etl:migrate-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate data from Postgres to MongoDB';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::connection('pgsql')->table('datasys')->orderBy('Data_x0020_pedido')->chunk(100, function (Collection $data) {
            // Process the records...
            foreach ($data as $row) {

                $mongoImport = [
                    'tenant_id' => '07636f9a-d75e-466a-a15a-13bba3311c85',
                    'data_pedido' => $row->Data_x0020_pedido,
                    'numero_pedido' => $row->Numero_x0020_Pedido,
                    'data' => $row,
                    'is_processed' => false,

                ];

                $mongodb = Import::create($mongoImport);
                $this->info('Migrated import with ID: ' . $mongodb->id);
            }
        });


        return $this->info('Data migration completed successfully.');
    }
}
