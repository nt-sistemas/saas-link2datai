<?php

namespace App\Filament\Resources\MetaFilials\Pages;

use App\Filament\Resources\MetaFilials\MetaFilialResource;
use App\Models\Filial;
use App\Models\MetaFilial;
use Filament\Resources\Pages\CreateRecord;

class CreateMetaFilial extends CreateRecord
{

    protected static string $resource = MetaFilialResource::class;

    public function canCreateAnother(): bool
    {
        return false;
    }


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
