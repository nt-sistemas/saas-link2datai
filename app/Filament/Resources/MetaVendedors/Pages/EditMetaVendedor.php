<?php

namespace App\Filament\Resources\MetaVendedors\Pages;

use App\Filament\Resources\MetaVendedors\MetaVendedorResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditMetaVendedor extends EditRecord
{
    protected static string $resource = MetaVendedorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
