<?php

namespace App\Filament\Resources\Vendedors;

use App\Filament\Resources\Vendedors\Pages\CreateVendedor;
use App\Filament\Resources\Vendedors\Pages\EditVendedor;
use App\Filament\Resources\Vendedors\Pages\ListVendedors;
use App\Filament\Resources\Vendedors\Schemas\VendedorForm;
use App\Filament\Resources\Vendedors\Tables\VendedorsTable;
use App\Models\Vendedor;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VendedorResource extends Resource
{
    protected static ?string $model = Vendedor::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Vendedores';
    protected static ?string $modelLabel = 'Vendedor';
    protected static ?string $pluralModelLabel = 'Vendedores';
    protected static ?string $navigationLabel = 'Vendedores';

    public static function form(Schema $schema): Schema
    {
        return VendedorForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VendedorsTable::configure($table);
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
            'index' => ListVendedors::route('/'),
            'create' => CreateVendedor::route('/create'),
            'edit' => EditVendedor::route('/{record}/edit'),
        ];
    }
}
