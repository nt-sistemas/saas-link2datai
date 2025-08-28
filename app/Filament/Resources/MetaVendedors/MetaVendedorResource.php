<?php

namespace App\Filament\Resources\MetaVendedors;

use App\Filament\Resources\MetaVendedors\Pages\CreateMetaVendedor;
use App\Filament\Resources\MetaVendedors\Pages\EditMetaVendedor;
use App\Filament\Resources\MetaVendedors\Pages\ListMetaVendedors;
use App\Filament\Resources\MetaVendedors\Pages\ViewMetaVendedor;
use App\Filament\Resources\MetaVendedors\Schemas\MetaVendedorForm;
use App\Filament\Resources\MetaVendedors\Schemas\MetaVendedorInfolist;
use App\Filament\Resources\MetaVendedors\Tables\MetaVendedorsTable;
use App\Models\MetaVendedor;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MetaVendedorResource extends Resource
{
    protected static ?string $model = MetaVendedor::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Meta Vendedor';
    protected static string|\UnitEnum|null $navigationGroup = 'Ajustes de Metas';


    protected static ?string $modelLabel = 'Meta Vendedor';
    protected static ?string $pluralModelLabel = 'Metas Vendedores';
    protected static ?string $navigationLabel = 'Metas Vendedores';

    public static function form(Schema $schema): Schema
    {
        return MetaVendedorForm::configure($schema);
    }


    public static function table(Table $table): Table
    {
        return MetaVendedorsTable::configure($table);
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
            'index' => ListMetaVendedors::route('/'),
            'create' => CreateMetaVendedor::route('/create'),
            'view' => ViewMetaVendedor::route('/{record}'),
            'edit' => EditMetaVendedor::route('/{record}/edit'),
        ];
    }
}
