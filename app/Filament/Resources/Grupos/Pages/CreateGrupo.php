<?php

namespace App\Filament\Resources\Grupos\Pages;

use App\Filament\Resources\Grupos\GrupoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGrupo extends CreateRecord
{
    protected static string $resource = GrupoResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $data['tenant_id'] = auth()->user()->tenant_id;

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
