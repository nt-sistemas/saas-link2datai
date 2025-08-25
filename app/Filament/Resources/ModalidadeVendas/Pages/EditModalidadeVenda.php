<?php

namespace App\Filament\Resources\ModalidadeVendas\Pages;

use App\Filament\Resources\ModalidadeVendas\ModalidadeVendaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditModalidadeVenda extends EditRecord
{
    protected static string $resource = ModalidadeVendaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
