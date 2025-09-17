<?php

namespace App\Filament\Resources\Vendedors\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class VendedorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('document')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                Select::make('filial_id')
                    ->label('Filial')
                    ->options(function () {
                        return \App\Models\Filial::pluck('name', 'id');
                    })
                    ->searchable()
                    ->preload(),
            ]);
    }
}
