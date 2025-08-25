<?php

namespace App\Observers;

use App\Models\GrupoEstoque;

class GrupoEstoqueObserver
{
    /**
     * Handle the GrupoEstoque "created" event.
     */
    public function created(GrupoEstoque $grupoEstoque): void
    {
        //
    }

    public function creating(GrupoEstoque $grupoEstoque): void
    {
        $grupoEstoque->tenant_id = auth()->user()->tenant_id;
    }

    public function updating(GrupoEstoque $grupoEstoque): void
    {
        $grupoEstoque->tenant_id = auth()->user()->tenant_id;
    }

    /**
     * Handle the GrupoEstoque "updated" event.
     */
    public function updated(GrupoEstoque $grupoEstoque): void
    {
        //
    }

    /**
     * Handle the GrupoEstoque "deleted" event.
     */
    public function deleted(GrupoEstoque $grupoEstoque): void
    {
        //
    }

    /**
     * Handle the GrupoEstoque "restored" event.
     */
    public function restored(GrupoEstoque $grupoEstoque): void
    {
        //
    }

    /**
     * Handle the GrupoEstoque "force deleted" event.
     */
    public function forceDeleted(GrupoEstoque $grupoEstoque): void
    {
        //
    }
}