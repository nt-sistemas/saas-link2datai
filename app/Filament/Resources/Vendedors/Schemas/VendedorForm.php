<?php

namespace App\Filament\Resources\Vendedors\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

class VendedorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('document')
                    ->label('CPF')
                    ->required(fn(string $context) => $context === 'create')
                    ->rule(static function (Get $get, string $context): \Closure {
                        return function ($attribute, $value, $fail) use ($get, $context) {
                            if ($context === 'create') {
                                $vendedorExists = \App\Models\Vendedor::where('document', strtoupper($value))
                                    ->where('tenant_id', auth()->user()->tenant_id)
                                    ->exists();

                                if ($vendedorExists) {
                                    $fail('Este CPF já está cadastrado.');
                                }
                            }
                        };
                    })
                    ->rules([new \App\Class\Uitl\ValidarCPF()]),
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
