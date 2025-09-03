<?php

namespace App\Imports;

use App\Models\Import;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Events\ImportFailed;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class UploadImport implements ToModel, WithHeadingRow, WithColumnFormatting, WithChunkReading, ShouldQueue, WithEvents
{
    public $tenant_id;
    public $upload_id;
    private $rows = 0;

    public function __construct($tenant_id, $upload_id = null)
    {
        $this->tenant_id = $tenant_id;
        $this->upload_id = $upload_id;
    }

    public function model(array $row)
    {
        ++$this->rows;

        $data_pedido = Date::excelToDateTimeObject($row['data_pedido']);
        $row['tenant_id'] = $this->tenant_id;

        $row['data_pedido'] = Carbon::parse($data_pedido)->format('Y-m-d');
        ds($row);

        $mongoImport = [
            'tenant_id' => $this->tenant_id,
            'data_pedido' => Carbon::parse($data_pedido)->format('Y-m-d'),
            'numero_pedido' => $row['numero_pv'],
            'data' => $row,
            'is_processed' => false,
        ];

        return Import::create($mongoImport);

    }

    public function columnFormats(): array
    {
        return [
            'H' => NumberFormat::FORMAT_DATE_DDMMYYYY,

        ];
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function (BeforeImport $event) {
                Log::info('Starting import for file: ' . $this->tenant_id);
                //$upload = \App\Models\Upload::find($this->upload_id);


            },
            AfterImport::class => function (AfterImport $event) {
                //\Artisan::call('datasys:etl');
                Log::info('Import completed successfully.');


                \Artisan::call('etl:process-import');
            },
            ImportFailed::class => function (ImportFailed $event) {
                Log::error('Import failed', ['error' => $event->getException()->getMessage()]);
            },
        ];
    }

    public function getRowCount(): int
    {
        return $this->rows;
    }
}
