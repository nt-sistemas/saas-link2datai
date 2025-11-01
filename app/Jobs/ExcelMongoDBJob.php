<?php

namespace App\Jobs;

use App\Imports\UploadImport;
use App\Models\Import;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Maatwebsite\Excel\Facades\Excel;

class ExcelMongoDBJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public $data) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Import::insert($this->data);
    }
}
