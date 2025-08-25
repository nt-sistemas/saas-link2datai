<?php

namespace App\Observers;

use App\Models\Categoria;

class CategoriaObserver
{
    /**
     * Handle the Categoria "created" event.
     */
    public function created(Categoria $categoria): void
    {
        //
    }

    public function creating(Categoria $categoria): void
    {
        $categoria->tenant_id = auth()->user()->tenant_id;
    }

    public function updating(Categoria $categoria): void
    {
        $categoria->tenant_id = auth()->user()->tenant_id;
    }

    /**
     * Handle the Categoria "updated" event.
     */
    public function updated(Categoria $categoria): void
    {
        //
    }

    /**
     * Handle the Categoria "deleted" event.
     */
    public function deleted(Categoria $categoria): void
    {
        //
    }

    /**
     * Handle the Categoria "restored" event.
     */
    public function restored(Categoria $categoria): void
    {
        //
    }

    /**
     * Handle the Categoria "force deleted" event.
     */
    public function forceDeleted(Categoria $categoria): void
    {
        //
    }
}