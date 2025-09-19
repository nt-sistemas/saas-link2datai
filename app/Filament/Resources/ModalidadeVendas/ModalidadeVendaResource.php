<?php

namespace App\Filament\Resources\ModalidadeVendas;

use App\Filament\Resources\ModalidadeVendas\Pages\CreateModalidadeVenda;
use App\Filament\Resources\ModalidadeVendas\Pages\EditModalidadeVenda;
use App\Filament\Resources\ModalidadeVendas\Pages\ListModalidadeVendas;
use App\Filament\Resources\ModalidadeVendas\Schemas\ModalidadeVendaForm;
use App\Filament\Resources\ModalidadeVendas\Tables\ModalidadeVendasTable;
use App\Models\ModalidadeVenda;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ModalidadeVendaResource extends Resource
{
    protected static ?string $model = ModalidadeVenda::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBriefcase;

    protected static ?string $recordTitleAttribute = 'Modalidades de Vendas';
    protected static ?string $modelLabel = 'Modalidade de Venda';
    protected static ?string $pluralModelLabel = 'Modalidades de Vendas';
    protected static ?string $navigationLabel = 'Modalidades de Vendas';
    protected static ?int $navigationSort = 8;

    public static function form(Schema $schema): Schema
    {
        return ModalidadeVendaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ModalidadeVendasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListModalidadeVendas::route('/'),
            'create' => CreateModalidadeVenda::route('/create'),
            'edit' => EditModalidadeVenda::route('/{record}/edit'),
        ];
    }
}
