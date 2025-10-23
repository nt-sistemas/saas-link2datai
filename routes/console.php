<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');



Schedule::command('etl:process-import')
    ->everyTwoMinutes()
    ->withoutOverlapping()
    ->runInBackground()
    ->onOneServer()
    ->appendOutputTo(storage_path('logs/etl-process-import.log'));
