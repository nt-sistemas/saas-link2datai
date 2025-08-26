<?php

namespace App\Filament\Resources\MetaFilials\Pages;

use App\Filament\Resources\MetaFilials\MetaFilialResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMetaFilial extends ViewRecord
{
    protected static string $resource = MetaFilialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
