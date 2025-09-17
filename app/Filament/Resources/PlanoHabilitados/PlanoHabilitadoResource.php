<?php

namespace App\Filament\Resources\PlanoHabilitados;

use App\Filament\Resources\PlanoHabilitados\Pages\CreatePlanoHabilitado;
use App\Filament\Resources\PlanoHabilitados\Pages\EditPlanoHabilitado;
use App\Filament\Resources\PlanoHabilitados\Pages\ListPlanoHabilitados;
use App\Filament\Resources\PlanoHabilitados\Schemas\PlanoHabilitadoForm;
use App\Filament\Resources\PlanoHabilitados\Tables\PlanoHabilitadosTable;
use App\Models\PlanoHabilitado;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PlanoHabilitadoResource extends Resource
{
    protected static ?string $model = PlanoHabilitado::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDevicePhoneMobile;

    protected static ?string $recordTitleAttribute = 'Planos Habilitados';
    protected static ?string $modelLabel = 'Plano Habilitado';
    protected static ?string $pluralModelLabel = 'Planos Habilitados';
    protected static ?string $navigationLabel = 'Planos Habilitados';

    public static function form(Schema $schema): Schema
    {
        return PlanoHabilitadoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PlanoHabilitadosTable::configure($table);
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
            'index' => ListPlanoHabilitados::route('/'),
            'create' => CreatePlanoHabilitado::route('/create'),
            'edit' => EditPlanoHabilitado::route('/{record}/edit'),
        ];
    }
}
