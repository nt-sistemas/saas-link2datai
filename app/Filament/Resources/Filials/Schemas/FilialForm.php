<?php

namespace App\Filament\Resources\Filials\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class FilialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextInput::make('code'),
                TextInput::make('name')
                    ->required(),
            ]);
    }
}