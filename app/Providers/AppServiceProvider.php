<?php

namespace App\Providers;

use App\Observers\GrupoEstoqueObserver;
use App\Models\GrupoEstoque;
use App\Observers\PlanoHabilitadoObserver;
use App\Observers\ModalidadeVendaObserver;
use App\Observers\TipoGrupoObserver;
use App\Models\TipoGrupo;
use App\Observers\GrupoObserver;
use App\Models\Grupo;
use App\Observers\CategoriaObserver;
use App\Observers\VendedorObserver;
use App\Observers\FilialObserver;
use App\Models\Categoria;
use App\Models\Filial;
use App\Models\ModalidadeVenda;
use App\Models\PlanoHabilitado;
use App\Models\User;
use App\Models\Vendedor;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;
use Mary\View\Components\Modal;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        User::observe(UserObserver::class);
        Filial::observe(FilialObserver::class);
        Vendedor::observe(VendedorObserver::class);
        Categoria::observe(CategoriaObserver::class);
        Grupo::observe(GrupoObserver::class);
        TipoGrupo::observe(TipoGrupoObserver::class);
        //ModalidadeVenda::observe(ModalidadeVendaObserver::class);
        //PlanoHabilitado::observe(PlanoHabilitadoObserver::class);
        //GrupoEstoque::observe(GrupoEstoqueObserver::class);


    }
}
