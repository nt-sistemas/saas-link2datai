<?php

namespace App\Observers;

use App\Models\Import;
use App\Models\Venda;

class VendaObserver
{
    /**
     * Handle the Venda "created" event.
     */
    public function created(Venda $venda): void {}

    public function creating(Venda $venda): void {}

    /**
     * Handle the Venda "updated" event.
     */
    public function updated(Venda $venda): void
    {
        //
    }

    /**
     * Handle the Venda "deleted" event.
     */
    public function deleted(Venda $venda): void
    {
        //
    }

    /**
     * Handle the Venda "restored" event.
     */
    public function restored(Venda $venda): void
    {
        //
    }

    /**
     * Handle the Venda "force deleted" event.
     */
    public function forceDeleted(Venda $venda): void
    {
        //
    }
}
