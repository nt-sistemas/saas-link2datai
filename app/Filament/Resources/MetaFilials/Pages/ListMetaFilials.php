<?php

namespace App\Filament\Resources\MetaFilials\Pages;

use App\Filament\Resources\MetaFilials\MetaFilialResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMetaFilials extends ListRecords
{
    protected static string $resource = MetaFilialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
