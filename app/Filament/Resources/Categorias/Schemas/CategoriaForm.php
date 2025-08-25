<?php

namespace App\Filament\Resources\Categorias\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CategoriaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextInput::make('name')
                ->label('Nome')
                    ->required(),
                TextInput::make('description')
                    ->label('Descrição'),
                TextInput::make('order')
                    ->label('Ordem')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('active')
                    ->label('Ativo')
                    ->required(),
            ]);
    }
}