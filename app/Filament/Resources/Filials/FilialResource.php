<?php

namespace App\Filament\Resources\Filials;

use App\Filament\Resources\Filials\Pages\CreateFilial;
use App\Filament\Resources\Filials\Pages\EditFilial;
use App\Filament\Resources\Filials\Pages\ListFilials;
use App\Filament\Resources\Filials\Schemas\FilialForm;
use App\Filament\Resources\Filials\Tables\FilialsTable;
use App\Models\Filial;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FilialResource extends Resource
{
    protected static ?string $model = Filial::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

       protected static ?string $recordTitleAttribute = 'Filiais';
    protected static ?string $modelLabel = 'Filial';
    protected static ?string $pluralModelLabel = 'Filiais';
    protected static ?string $navigationLabel = 'Filiais';
    protected static string|\UnitEnum|null $navigationGroup = 'Configurações';
    public static function form(Schema $schema): Schema
    {
        return FilialForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FilialsTable::configure($table);
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
            'index' => ListFilials::route('/'),
            'create' => CreateFilial::route('/create'),
            'edit' => EditFilial::route('/{record}/edit'),
        ];
    }
}
