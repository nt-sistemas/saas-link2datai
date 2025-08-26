<?php

namespace App\Filament\Resources\MetaFilials\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MetaFilialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('month')
                    ->label('Mes')
                    ->placeholder('Selecione o Mês')
                    ->options([
                        '01' => 'Janeiro',
                        '02' => 'Fevereiro',
                        '03' => 'Março',
                        '04' => 'Abril',
                        '05' => 'Maio',
                        '06' => 'Junho',
                        '07' => 'Julho',
                        '08' => 'Agosto',
                        '09' => 'Setembro',
                        '10' => 'Outubro',
                        '11' => 'Novembro',
                        '12' => 'Dezembro',
                    ])->required(),

                TextInput::make('year')
                    ->label('Ano')
                    ->numeric()
                    ->required()
                    ->minValue(2000)
                    ->maxValue(2100)
                    ->default(date('Y'))
                    ->placeholder('2025'),
                TextInput::make('meta')
                    ->label('Meta')
                    ->numeric()
                    ->inputMode('decimal')
                    ->required()
                    ->placeholder('0.00'),
                TextInput::make('quant')
                    ->label('Quantidade')
                    ->numeric()
                    ->required()
                    ->placeholder('0'),
            ]);
    }
}
