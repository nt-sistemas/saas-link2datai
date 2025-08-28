<?php

namespace App\Filament\Resources\MetaVendedors\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MetaVendedorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('grupo.name')
                    ->label('Grupo')
                    ->searchable(),
                TextColumn::make('month')
                    ->label('Mes')
                    ->searchable(),
                TextColumn::make('year')
                    ->label('Ano')
                    ->searchable(),
                TextColumn::make('vendedor.name')
                    ->label('Vendedor')
                    ->searchable(),
                TextColumn::make('meta')
                    ->label('Meta')
                    ->money("BRL", true)
                    ->searchable(),
                TextColumn::make('quant')
                    ->label('Quantidade')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
