<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class VoltarPrincipal extends Page
{
    protected static string|null|\BackedEnum $navigationIcon = Heroicon::HomeModern;

    protected static ?string $navigationLabel = 'Voltar ao Dashboard';

    public function mount(): void
    {
        redirect()->route('app.dashboard');
    }

    // protected string $view = 'filament.pages.voltar-principal';
}
