<?php

namespace App\Filament\Resources\TipoGrupos\Pages;

use App\Filament\Resources\TipoGrupos\TipoGrupoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTipoGrupo extends EditRecord
{
    protected static string $resource = TipoGrupoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
