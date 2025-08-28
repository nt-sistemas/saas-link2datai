<?php

namespace App\Filament\Resources\MetaVendedors\Pages;

use App\Filament\Resources\MetaVendedors\MetaVendedorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMetaVendedors extends ListRecords
{
    protected static string $resource = MetaVendedorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
