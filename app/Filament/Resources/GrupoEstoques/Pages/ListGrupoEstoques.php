<?php

namespace App\Filament\Resources\GrupoEstoques\Pages;

use App\Filament\Resources\GrupoEstoques\GrupoEstoqueResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGrupoEstoques extends ListRecords
{
    protected static string $resource = GrupoEstoqueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
