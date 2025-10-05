<?php

namespace App\Filament\Resources\Imports\Schemas;

use Filament\Actions\Action;
use Filament\Forms\Components\KeyValue;
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
                TextInput::make('message')
                    ->label('Mensagem')
                    ->disabled(),
                TextInput::make('data_pedido')
                    ->label('Data do Pedido'),

                Toggle::make('is_processed')
                    ->label('Processado'),
                TextInput::make('message_error')
                    ->label('Mensagem de Erro'),
                TextInput::make('numero_pedido')
                    ->label('Número do Pedido'),
                KeyValue::make('data')
                    ->label('Dados Importados')
                    ->columnSpanFull(),
            ]);
    }

    protected static function getActions(): array
    {
        return [
            Action::make('processar_importacao')
                ->label('Processar Importação')
                ->action('processImport')
                ->color('success')
                ->icon('heroicon-o-arrow-up-tray'),
        ];
    }
}
