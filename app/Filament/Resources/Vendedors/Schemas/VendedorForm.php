<?php

namespace App\Filament\Resources\Vendedors\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

class VendedorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('document')
                    ->label('CPF')
                    ->required(),

                TextInput::make('name')
                    ->label('Nome')
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
