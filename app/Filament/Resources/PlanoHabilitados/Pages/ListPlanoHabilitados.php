<?php

namespace App\Filament\Resources\PlanoHabilitados\Pages;

use App\Filament\Resources\PlanoHabilitados\PlanoHabilitadoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPlanoHabilitados extends ListRecords
{
    protected static string $resource = PlanoHabilitadoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //CreateAction::make(),
        ];
    }
}