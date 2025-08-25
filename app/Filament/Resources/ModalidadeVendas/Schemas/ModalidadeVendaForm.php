<?php

namespace App\Filament\Resources\ModalidadeVendas\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ModalidadeVendaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('tenant_id')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('description'),
            ]);
    }
}
