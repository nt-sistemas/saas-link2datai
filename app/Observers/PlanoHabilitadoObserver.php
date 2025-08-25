<?php

namespace App\Observers;

use App\Models\PlanoHabilitado;

class PlanoHabilitadoObserver
{
    /**
     * Handle the PlanoHabilitado "created" event.
     */
    public function created(PlanoHabilitado $planoHabilitado): void
    {
        //
    }

    public function creating(PlanoHabilitado $planoHabilitado): void
    {
        $planoHabilitado->tenant_id = auth()->user()->tenant_id;
    }

    public function updating(PlanoHabilitado $planoHabilitado): void
    {
        $planoHabilitado->tenant_id = auth()->user()->tenant_id;
    }

    /**
     * Handle the PlanoHabilitado "updated" event.
     */
    public function updated(PlanoHabilitado $planoHabilitado): void
    {
        //
    }

    /**
     * Handle the PlanoHabilitado "deleted" event.
     */
    public function deleted(PlanoHabilitado $planoHabilitado): void
    {
        //
    }

    /**
     * Handle the PlanoHabilitado "restored" event.
     */
    public function restored(PlanoHabilitado $planoHabilitado): void
    {
        //
    }

    /**
     * Handle the PlanoHabilitado "force deleted" event.
     */
    public function forceDeleted(PlanoHabilitado $planoHabilitado): void
    {
        //
    }
}