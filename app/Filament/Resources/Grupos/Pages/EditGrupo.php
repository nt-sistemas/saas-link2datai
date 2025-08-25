<?php

namespace App\Filament\Resources\Grupos\Pages;

use App\Filament\Resources\Grupos\GrupoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGrupo extends EditRecord
{
    protected static string $resource = GrupoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
