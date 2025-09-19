<?php

namespace App\Filament\Resources\Metas\Schemas;

use App\Models\Filial;
use App\Models\Venda;
use App\Models\Vendedor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Collection;
use Mary\View\Components\Select;
use function Pest\Laravel\options;

class MetaForm
{
    /**
     * @throws \Exception
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('grupo_id')
                    ->label('Grupo')
                    ->relationship('grupo', 'name')
                    ->preload()
                    ->searchable()
                    ->required(),
                \Filament\Forms\Components\Select::make('filial_id')
                    ->label('Filial')
                    ->options(fn(Get $get): Collection => Filial::query()
                        ->where('tenant_id', auth()->user()->tenant_id)
                        ->orderBy('name', 'asc')
                        ->pluck('name', 'id'))
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->live()
                    ->required(),
                \Filament\Forms\Components\Select::make('vendedor_id')
                    ->options(fn(Get $get): Collection => Vendedor::query()
                        ->whereIn('filial_id', $get('filial_id'))
                        ->orderBy('name', 'asc')
                        ->pluck('name', 'id'))
                    ->searchable()
                    ->multiple()
                    ->preload(),


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
}
