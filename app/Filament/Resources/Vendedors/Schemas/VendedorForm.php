<?php

namespace App\Filament\Resources\Vendedors\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class VendedorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('tenant_id')
                    ->required(),
                TextInput::make('document')
                    ->required(),
                TextInput::make('name')
                    ->required(),
            ]);
    }
}
