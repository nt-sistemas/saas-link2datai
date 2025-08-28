<?php

namespace App\Filament\Resources\MetaFilials;

use App\Filament\Resources\MetaFilials\Pages\CreateMetaFilial;
use App\Filament\Resources\MetaFilials\Pages\EditMetaFilial;
use App\Filament\Resources\MetaFilials\Pages\ListMetaFilials;
use App\Filament\Resources\MetaFilials\Pages\ViewMetaFilial;
use App\Filament\Resources\MetaFilials\Schemas\MetaFilialForm;
use App\Filament\Resources\MetaFilials\Schemas\MetaFilialInfolist;
use App\Filament\Resources\MetaFilials\Tables\MetaFilialsTable;
use App\Models\MetaFilial;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MetaFilialResource extends Resource
{
    protected static ?string $model = MetaFilial::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|\UnitEnum|null $navigationGroup = 'Ajustes de Metas';

    protected static ?string $recordTitleAttribute = 'Metas Filiais';
    protected static ?string $modelLabel = 'Meta Filial';
    protected static ?string $pluralModelLabel = 'Metas Filiais';
    protected static ?string $navigationLabel = 'Metas Filiais';

    public static function form(Schema $schema): Schema
    {
        return MetaFilialForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return MetaFilialInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MetaFilialsTable::configure($table);
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
            'index' => ListMetaFilials::route('/'),
            'create' => CreateMetaFilial::route('/create'),
            'view' => ViewMetaFilial::route('/{record}'),
            'edit' => EditMetaFilial::route('/{record}/edit'),
        ];
    }
}
