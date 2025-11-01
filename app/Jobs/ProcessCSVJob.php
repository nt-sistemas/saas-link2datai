<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class ProcessCSVJob implements ShouldQueue
{
    use  Queueable;


    public $data;
    public $filename;

    /**
     * Create a new job instance.
     */
    public function __construct(array $data, string $filename)
    {
        $this->data = $data;
        $this->filename = $filename;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $batchInserts = [];
        foreach ($this->data as $record) {
            $batchInserts[] = [
                'tenant_id' => auth()->user()->tenant_id,
                'filename' => $this->filename,
                'data_pedido' => new Date($record['Data Pedido'], 'Y-m-d'),
                'numero_pedido' => $record['Número PV'],
                'data' => json_encode($record),
                'is_processed' => false,
            ];

            if (count($batchInserts) >= 1000) {
                //ExcelMongoDBJob::dispatch($batchInserts);

                $batchInserts = [];
            }
        }

        if (!empty($batchInserts)) {
            //ExcelMongoDBJob::dispatch($batchInserts);


            $batchInserts = [];
        }
    }
}
