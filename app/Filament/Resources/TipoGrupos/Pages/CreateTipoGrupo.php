<?php

namespace App\Filament\Resources\TipoGrupos\Pages;

use App\Filament\Resources\TipoGrupos\TipoGrupoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTipoGrupo extends CreateRecord
{
    protected static string $resource = TipoGrupoResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $data['tenant_id'] = auth()->user()->tenant_id;

        return $data;
    }
}
