<?php

namespace App\Filament\Resources\Metas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MetasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('grupo.name')
                    ->label('Grupo')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('filial.name')
                    ->label('Filial')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('vendedor.name')
                    ->label('Vendedor')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('mes')
                    ->sortable(),
                TextColumn::make('ano')
                    ->sortable(),
                TextColumn::make('valor_meta')
                    ->label('Valor')
                    ->money('BRL')
                    ->sortable(),
                TextColumn::make('quantidade')
                    ->label('Quantidade')
                    ->numeric()
                    ->sortable(),

            ])
            ->filters([
                //
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
