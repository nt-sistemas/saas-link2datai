<?php

namespace App\Filament\Resources\Imports\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ImportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('data')
                    ->json(),
                TextInput::make('data_pedido'),
                Toggle::make('is_processed'),
                TextInput::make('message_error'),
                TextInput::make('numero_pedido'),
                TextInput::make('tenant_id'),
            ]);
    }
}
