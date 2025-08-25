<?php

namespace App\Observers;

use App\Models\Grupo;

class GrupoObserver
{
    /**
     * Handle the Grupo "created" event.
     */
    public function created(Grupo $grupo): void
    {
        //
    }

    public function creating(Grupo $grupo): void
    {
        $grupo->tenant_id = auth()->user()->tenant_id;
    }

    public function updating(Grupo $grupo): void
    {
        $grupo->tenant_id = auth()->user()->tenant_id;
    }

    /**
     * Handle the Grupo "updated" event.
     */
    public function updated(Grupo $grupo): void
    {
        //
    }

    /**
     * Handle the Grupo "deleted" event.
     */
    public function deleted(Grupo $grupo): void
    {
        //
    }

    /**
     * Handle the Grupo "restored" event.
     */
    public function restored(Grupo $grupo): void
    {
        //
    }

    /**
     * Handle the Grupo "force deleted" event.
     */
    public function forceDeleted(Grupo $grupo): void
    {
        //
    }
}