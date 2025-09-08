<?php

namespace App\Filament\Resources\Imports\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;

class ImportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('numero_pedido')
                    ->label('Número do Pedido')
                    ->searchable(),
                TextColumn::make('data_pedido')
                    ->label('Data do Pedido')
                    ->searchable(),
                IconColumn::make('is_processed')
                    ->label('Processado')

                    ->boolean(),
                TextColumn::make('message_error')
                    ->label('Mensagem de Erro')
                    ->searchable(),

            ])
            ->filters([
                Filter::make('message_error')
                    ->label('Erros')
                    ->query(fn($query) => $query->where('message_error', '!=', null)),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
