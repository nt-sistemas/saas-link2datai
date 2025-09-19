<?php

namespace App\Filament\Resources\Vendedors\Pages;

use App\Filament\Resources\Vendedors\VendedorResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use PhpParser\Node\Expr\Throw_;

class CreateVendedor extends CreateRecord
{
    protected static string $resource = VendedorResource::class;
    protected static bool $canCreateAnother = false;


    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $vendedorExists = VendedorResource::getModel()::where('document', strtoupper($data['document']))
            ->where('tenant_id', auth()->user()->tenant_id)
            ->exists();


        $data['tenant_id'] = auth()->user()
            ->tenant_id;
        $data['name'] = strtoupper($data['name']);

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
