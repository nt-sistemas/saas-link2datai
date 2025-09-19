<?php

namespace App\Filament\Resources\Vendedors\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class VendedorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('document')
                    ->label('CPF')
                    ->required()
                    ->rule(static function (Get $get, Component $component): \Closure {
                        return function ($attribute, $value, $fail) use ($get, $component) {
                            $vendedorExists = \App\Models\Vendedor::where('document', strtoupper($value))
                                ->where('tenant_id', auth()->user()->tenant_id)
                                ->exists();

                            if ($vendedorExists) {
                                $fail('Este CPF já está cadastrado.');
                            }
                        };
                    }),
                TextInput::make('name')
                    ->label('Nome')
                    ->required(),
                Select::make('filial_id')
                    ->label('Filial')
                    ->options(function () {
                        return \App\Models\Filial::pluck('name', 'id');
                    })
                    ->searchable()
                    ->preload(),
            ]);
    }


}
