<?php

namespace App\Filament\Resources\PlanoHabilitados\Pages;

use App\Filament\Resources\PlanoHabilitados\PlanoHabilitadoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPlanoHabilitado extends EditRecord
{
    protected static string $resource = PlanoHabilitadoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
