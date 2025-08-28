<?php

namespace App\Filament\Resources\MetaVendedors\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class MetaVendedorInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('tenant_id'),
                TextEntry::make('month'),
                TextEntry::make('year')
                    ->numeric(),
                TextEntry::make('vendedor_id'),
                TextEntry::make('meta')
                    ->numeric(),
                TextEntry::make('quant')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
                TextEntry::make('grupo_id'),
            ]);
    }
}
