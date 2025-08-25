<?php

namespace App\Filament\Widgets;

use App\Models\Import;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ImportsOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Importação';

    protected ?string $description = 'Uma visão geral da Importação dos Dados para Link2Data Intelligence.';
    protected function getStats(): array
    {
        return [
            Stat::make('Total de Dados', number_format(Import::count(),0,',','.')),
            Stat::make('Importados', number_format(Import::where('is_processed', true)->count(),0,',','.')),
            Stat::make('Não Importados', number_format(Import::where('is_processed', false)->count(),0,',','.')),
            Stat::make('Total da Ultima Importação', number_format(Import::where('created_at', '>=', now()->subDay())->count(),0,',','.')),
            Stat::make('Erros de Importação', number_format(Import::whereNotNull('message_error')->count(),0,',','.')),

        ];
    }
}
