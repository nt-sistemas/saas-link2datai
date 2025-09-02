<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
            ds($tenant->uploads()->where('status', 'pending')->get());
        }
    }
}
