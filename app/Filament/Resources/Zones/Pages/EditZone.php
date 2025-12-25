<?php

namespace App\Filament\Resources\Zones\Pages;

use App\Filament\Resources\Zones\RelationManagers\FilialsRelationManager;
use App\Filament\Resources\Zones\ZoneResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditZone extends EditRecord
{
    protected static string $resource = ZoneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    public static function getRelations(): array
    {
        return [
            FilialsRelationManager::class,
        ];
    }
}
