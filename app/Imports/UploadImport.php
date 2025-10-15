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

class UploadImport implements ToModel, WithHeadingRow, WithColumnFormatting, WithEvents, WithChunkReading, ShouldQueue
{
    public $timeout = 1800;

    public $tenant_id;
    public $upload_id;
    private $rows = 0;
    public $filename;
    public $failOnTimeout = false;

    public function __construct($tenant_id, $upload_id = null, $filename = null)
    {
        $this->tenant_id = $tenant_id;
        $this->upload_id = $upload_id;
        $this->filename = $filename;
    }

    public function model(array $row)
    {
        ++$this->rows;
        ini_set('memory_limit', '-1');

        $data_pedido = Date::excelToDateTimeObject($row['data_pedido']);


        $row['data_pedido'] = Carbon::parse($data_pedido)->format('Y-m-d');


        $mongoImport = [
            'tenant_id' => $this->tenant_id,
            'filename' => $this->filename,
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



    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function (BeforeImport $event) {
                Log::info('Starting import for file: ' . $this->tenant_id);
                $upload = \App\Models\Upload::find($this->upload_id);
                if ($upload) {
                    $upload->status = 'processing';
                    $upload->save();
                }
            },
            AfterImport::class => function (AfterImport $event) {
                //\Artisan::call('datasys:etl');
                Log::info('Import completed successfully.');
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

    public function chunkSize(): int
    {
        return 500;
    }
}
