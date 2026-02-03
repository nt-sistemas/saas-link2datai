<?php

namespace App\Filament\Resources\Imports\Pages;

use App\Filament\Resources\Imports\ImportResource;
use Filament\Resources\Pages\CreateRecord;

class CreateImport extends CreateRecord
{
    protected static string $resource = ImportResource::class;

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
