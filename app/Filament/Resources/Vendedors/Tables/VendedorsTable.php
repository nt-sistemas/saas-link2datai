<?php

namespace App\Filament\Resources\Vendedors\Tables;

use App\Livewire\Admin\Categories\Create;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VendedorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),
                TextColumn::make('filial.name')
                    ->label('Filial')
                    ->searchable(),

                TextColumn::make('document')
                    ->label('Documento')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([

                EditAction::make(),

            ])
            ->toolbarActions([
                BulkActionGroup::make([

                    //DeleteBulkAction::make(),
                ]),
            ]);
    }
}
