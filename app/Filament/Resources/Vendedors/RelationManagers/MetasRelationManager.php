<?php

namespace App\Filament\Resources\Vendedors\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MetasRelationManager extends RelationManager
{
    protected static string $relationship = 'metas';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('grupo_id')
                    ->label('Grupo')
                    ->relationship('grupo', 'name')
                    ->required(),
                Select::make('filial_id')
                    ->label('Filial')
                    ->relationship('filial', 'name')
                    ->required(),
                TextInput::make('ano')
                    ->required()
                    ->numeric(),
                TextInput::make('mes')
                    ->required()
                    ->numeric(),
                TextInput::make('valor_meta')
                    ->numeric(),
                TextInput::make('quantidade')
                    ->numeric(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Metas')
            ->columns([
                TextColumn::make('grupo.name')
                    ->label('Grupo')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('filial.name')
                    ->label('Filial')
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
                    ->sortable()
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
                AssociateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DissociateAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
