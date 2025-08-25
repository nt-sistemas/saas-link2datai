<?php

namespace App\Filament\Resources\GrupoEstoques;

use App\Filament\Resources\GrupoEstoques\Pages\CreateGrupoEstoque;
use App\Filament\Resources\GrupoEstoques\Pages\EditGrupoEstoque;
use App\Filament\Resources\GrupoEstoques\Pages\ListGrupoEstoques;
use App\Filament\Resources\GrupoEstoques\Schemas\GrupoEstoqueForm;
use App\Filament\Resources\GrupoEstoques\Tables\GrupoEstoquesTable;
use App\Models\GrupoEstoque;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GrupoEstoqueResource extends Resource
{
    protected static ?string $model = GrupoEstoque::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

       protected static ?string $recordTitleAttribute = 'Grupos de Estoques';
    protected static ?string $modelLabel = 'Grupo de Estoque';
    protected static ?string $pluralModelLabel = 'Grupos de Estoques';
    protected static ?string $navigationLabel = 'Grupos de Estoques';
    protected static string|\UnitEnum|null $navigationGroup = 'Ajustes Pedidos';
    public static function form(Schema $schema): Schema
    {
        return GrupoEstoqueForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GrupoEstoquesTable::configure($table);
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
            'index' => ListGrupoEstoques::route('/'),
            'create' => CreateGrupoEstoque::route('/create'),
            'edit' => EditGrupoEstoque::route('/{record}/edit'),
        ];
    }
}
