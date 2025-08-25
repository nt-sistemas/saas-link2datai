<?php

namespace App\Filament\Resources\PlanoHabilitados\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PlanoHabilitadoForm
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
