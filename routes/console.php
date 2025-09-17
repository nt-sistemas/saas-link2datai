<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('link2b:process-excel-file')
    ->everyFiveMinutes()
    ->withoutOverlapping()
    ->runInBackground()
    ->onOneServer()
    ->appendOutputTo(storage_path('logs/process-excel-file.log'));

Schedule::command('etl:process-import')
    ->everyFiveMinutes()
    ->withoutOverlapping()
    ->runInBackground()
    ->onOneServer()
    ->appendOutputTo(storage_path('logs/etl-process-import.log'));
