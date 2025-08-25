<?php

namespace App\Filament\Resources\GrupoEstoques\Pages;

use App\Filament\Resources\GrupoEstoques\GrupoEstoqueResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGrupoEstoque extends EditRecord
{
    protected static string $resource = GrupoEstoqueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //DeleteAction::make(),
        ];
    }
}