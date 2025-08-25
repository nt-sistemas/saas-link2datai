<?php

namespace App\Observers;

use App\Models\ModalidadeVenda;

class ModalidadeVendaObserver
{
    /**
     * Handle the ModalidadeVenda "created" event.
     */
    public function created(ModalidadeVenda $modalidadeVenda): void
    {
        //
    }

    public function creating(ModalidadeVenda $modalidadeVenda): void
    {
        $modalidadeVenda->tenant_id = auth()->user()->tenant_id;
    }

    public function updating(ModalidadeVenda $modalidadeVenda): void
    {
        $modalidadeVenda->tenant_id = auth()->user()->tenant_id;
    }

    /**
     * Handle the ModalidadeVenda "updated" event.
     */
    public function updated(ModalidadeVenda $modalidadeVenda): void
    {
        //
    }

    /**
     * Handle the ModalidadeVenda "deleted" event.
     */
    public function deleted(ModalidadeVenda $modalidadeVenda): void
    {
        //
    }

    /**
     * Handle the ModalidadeVenda "restored" event.
     */
    public function restored(ModalidadeVenda $modalidadeVenda): void
    {
        //
    }

    /**
     * Handle the ModalidadeVenda "force deleted" event.
     */
    public function forceDeleted(ModalidadeVenda $modalidadeVenda): void
    {
        //
    }
}