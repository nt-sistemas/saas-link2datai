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
        $data['filial_id'] = Filial::first()->id;

        return $data;
    }

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        ds($data);
        $filiais = Filial::where('tenant_id', auth()->user()->tenant_id)->get();
        foreach ($filiais as $filial) {
            $data['filial_id'] = $filial->id;
            static::getModel()::create($data);
        }
        return new (static::getModel());
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
