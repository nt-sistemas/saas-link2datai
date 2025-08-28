<?php

namespace App\Filament\Resources\MetaVendedors\Pages;

use App\Filament\Resources\MetaVendedors\MetaVendedorResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMetaVendedor extends CreateRecord
{
    protected static string $resource = MetaVendedorResource::class;


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
