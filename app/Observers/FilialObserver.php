<?php

namespace App\Observers;

use App\Models\Filial;

class FilialObserver
{
    /**
     * Handle the Filial "created" event.
     */
    public function created(Filial $filial): void
    {
        //
    }

    public function creating(Filial $filial): void
    {
        $filial->tenant_id = auth()->user()->tenant_id;
    }

    public function updating(Filial $filial): void
    {
        $filial->tenant_id = auth()->user()->tenant_id;
    }

    /**
     * Handle the Filial "updated" event.
     */
    public function updated(Filial $filial): void
    {
        //
    }

    /**
     * Handle the Filial "deleted" event.
     */
    public function deleted(Filial $filial): void
    {
        //
    }

    /**
     * Handle the Filial "restored" event.
     */
    public function restored(Filial $filial): void
    {
        //
    }

    /**
     * Handle the Filial "force deleted" event.
     */
    public function forceDeleted(Filial $filial): void
    {
        //
    }
}