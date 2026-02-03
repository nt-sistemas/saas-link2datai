<?php

namespace App\Filament\Resources\ModalidadeVendas\Pages;

use App\Filament\Resources\ModalidadeVendas\ModalidadeVendaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateModalidadeVenda extends CreateRecord
{
    protected static string $resource = ModalidadeVendaResource::class;

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
