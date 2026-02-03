<?php

namespace App\Filament\Resources\PlanoHabilitados\Pages;

use App\Filament\Resources\PlanoHabilitados\PlanoHabilitadoResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePlanoHabilitado extends CreateRecord
{
    protected static string $resource = PlanoHabilitadoResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $data['tenant_id'] = auth()->user()->tenant_id;

        return $data;
    }
}
