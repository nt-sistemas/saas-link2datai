<?php

namespace App\Observers;

use App\Models\TipoGrupo;

class TipoGrupoObserver
{
    /**
     * Handle the TipoGrupo "created" event.
     */
    public function created(TipoGrupo $tipoGrupo): void
    {
        //
    }

    public function creating(TipoGrupo $tipoGrupo): void
    {
        $tipoGrupo->tenant_id = auth()->user()->tenant_id;
    }

    public function updating(TipoGrupo $tipoGrupo): void
    {
        $tipoGrupo->tenant_id = auth()->user()->tenant_id;
    }

    /**
     * Handle the TipoGrupo "updated" event.
     */
    public function updated(TipoGrupo $tipoGrupo): void
    {
        //
    }

    /**
     * Handle the TipoGrupo "deleted" event.
     */
    public function deleted(TipoGrupo $tipoGrupo): void
    {
        //
    }

    /**
     * Handle the TipoGrupo "restored" event.
     */
    public function restored(TipoGrupo $tipoGrupo): void
    {
        //
    }

    /**
     * Handle the TipoGrupo "force deleted" event.
     */
    public function forceDeleted(TipoGrupo $tipoGrupo): void
    {
        //
    }
}