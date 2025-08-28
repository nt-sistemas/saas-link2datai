<?php

namespace App\Filament\Resources\MetaVendedors\Pages;

use App\Filament\Resources\MetaVendedors\MetaVendedorResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMetaVendedor extends ViewRecord
{
    protected static string $resource = MetaVendedorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
