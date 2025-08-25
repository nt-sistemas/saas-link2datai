<?php

namespace App\Filament\Resources\TipoGrupos\Pages;

use App\Filament\Resources\TipoGrupos\TipoGrupoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTipoGrupos extends ListRecords
{
    protected static string $resource = TipoGrupoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //CreateAction::make(),
        ];
    }
}