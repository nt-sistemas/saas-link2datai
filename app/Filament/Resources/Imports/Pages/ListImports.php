<?php

namespace App\Filament\Resources\Imports\Pages;

use App\Filament\Resources\Imports\ImportResource;
use App\Filament\Resources\Imports\Widgets\ImportsDiarioStats;
use App\Filament\Resources\Imports\Widgets\ImportsStats;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListImports extends ListRecords
{
    protected static string $resource = ImportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ImportsStats::class,
            ImportsDiarioStats::class,
        ];
    }
}
