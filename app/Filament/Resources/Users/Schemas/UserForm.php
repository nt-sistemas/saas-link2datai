<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\Cargo;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                Select::make('cargo_id')
                    ->label('Cargo')
                    ->options(Cargo::query()
                        ->pluck('name', 'id'))
                    ->required(),
                TextInput::make('password')
                    ->label('Senha')
                    ->password()
                    ->revealable(),
                Toggle::make('is_active')
                    ->label('Ativo')
                    ->required(),
            ]);
    }
}
