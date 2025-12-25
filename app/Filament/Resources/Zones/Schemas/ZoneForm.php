<?php

namespace App\Filament\Resources\Zones\Schemas;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ZoneForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nome')
                    ->required(),
                TextInput::make('description')
                    ->label('Descrição')
                    ->nullable(),
                Select::make('user_id')
                    ->label('Usuário')
                    ->options(User::query()
                        ->where('tenant_id', auth()->user()->tenant_id)
                        ->pluck('name', 'id')->toArray())
                    ->required(),
            ]);
    }
}
