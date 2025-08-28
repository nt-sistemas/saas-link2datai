<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class VoltarPrincipal extends Page
{
    protected static string|null|\BackedEnum $navigationIcon = Heroicon::HomeModern;

    public function mount(): void
    {
        redirect()->route('app.dashboard');
    }


    //protected string $view = 'filament.pages.voltar-principal';
}
