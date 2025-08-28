<?php

namespace App\Filament\Resources\MetaFilials\Pages;

use App\Filament\Resources\MetaFilials\MetaFilialResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditMetaFilial extends EditRecord
{
    protected static string $resource = MetaFilialResource::class;

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
