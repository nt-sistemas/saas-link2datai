<?php

namespace App\Filament\Resources\ModalidadeVendas\Pages;

use App\Filament\Resources\ModalidadeVendas\ModalidadeVendaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListModalidadeVendas extends ListRecords
{
    protected static string $resource = ModalidadeVendaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //CreateAction::make(),
        ];
    }
}