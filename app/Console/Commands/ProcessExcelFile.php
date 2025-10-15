<?php

namespace App\Console\Commands;

use App\Imports\UploadImport;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ProcessExcelFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'link2b:process-excel-file';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process an uploaded Excel file and update the database accordingly';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tenants = \App\Models\Tenant::all();
        foreach ($tenants as $tenant) {
            $upload = $tenant->uploads()->where('status', 'pending')->orderBy('created_at')->first();
            if (!$upload) {
                $this->info("No pending uploads for tenant: " . $tenant->name);
                $upload = $tenant->uploads()->where('status', 'processing')->first();

                continue;
            }

            $filePath = storage_path('app/public/' . $upload->attachment);
            if (!file_exists($filePath)) {
                $this->error("File not found: " . $filePath);
                continue;
            }
            $this->info("Processing file for tenant: " . $tenant->name);
            if ($upload->status === 'processing') {
                $this->info("Upload ID {$upload->id} is already being processed. Skipping.");
                continue;
            }
            $upload->status = 'processing';
            Excel::import(new UploadImport($tenant->id, $upload->id), $filePath);
            $upload->rows = new UploadImport($tenant->id, $upload->id)->getRowCount();
            $upload->save();
        }
    }
}
