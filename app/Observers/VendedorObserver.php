<?php

namespace App\Observers;

use App\Models\Vendedor;

class VendedorObserver
{
    /**
     * Handle the Vendedor "created" event.
     */
    public function created(Vendedor $vendedor): void
    {
        //
    }

    public function creating(Vendedor $vendedor): void
    {
        $vendedor->tenant_id = auth()->user()->tenant_id;
    }

    public function updating(Vendedor $vendedor): void
    {
        $vendedor->tenant_id = auth()->user()->tenant_id;
    }

    /**
     * Handle the Vendedor "updated" event.
     */
    public function updated(Vendedor $vendedor): void
    {
        //
    }

    /**
     * Handle the Vendedor "deleted" event.
     */
    public function deleted(Vendedor $vendedor): void
    {
        //
    }

    /**
     * Handle the Vendedor "restored" event.
     */
    public function restored(Vendedor $vendedor): void
    {
        //
    }

    /**
     * Handle the Vendedor "force deleted" event.
     */
    public function forceDeleted(Vendedor $vendedor): void
    {
        //
    }
}