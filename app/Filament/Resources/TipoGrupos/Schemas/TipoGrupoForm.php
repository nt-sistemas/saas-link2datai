<?php

namespace App\Filament\Resources\TipoGrupos\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TipoGrupoForm
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
