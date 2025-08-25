<?php

namespace App\Filament\Resources\Filials\Pages;

use App\Filament\Resources\Filials\FilialResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFilials extends ListRecords
{
    protected static string $resource = FilialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //CreateAction::make(),
        ];
    }
}