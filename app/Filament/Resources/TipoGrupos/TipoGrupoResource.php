<?php

namespace App\Filament\Resources\TipoGrupos;

use App\Filament\Resources\TipoGrupos\Pages\CreateTipoGrupo;
use App\Filament\Resources\TipoGrupos\Pages\EditTipoGrupo;
use App\Filament\Resources\TipoGrupos\Pages\ListTipoGrupos;
use App\Filament\Resources\TipoGrupos\Schemas\TipoGrupoForm;
use App\Filament\Resources\TipoGrupos\Tables\TipoGruposTable;
use App\Models\TipoGrupo;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TipoGrupoResource extends Resource
{
    protected static ?string $model = TipoGrupo::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCube;

    protected static ?string $recordTitleAttribute = 'Tipo de Pedidos';
    protected static ?string $modelLabel = 'tipo de pedido';
    protected static ?string $pluralModelLabel = 'tipos de pedido';
    protected static ?string $navigationLabel = 'Tipos de Pedido';
    protected static ?int $navigationSort = 1;



    public static function form(Schema $schema): Schema
    {
        return TipoGrupoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TipoGruposTable::configure($table);
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
            'index' => ListTipoGrupos::route('/'),
            'create' => CreateTipoGrupo::route('/create'),
            'edit' => EditTipoGrupo::route('/{record}/edit'),
        ];
    }
}
