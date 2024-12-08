<?php

namespace App\Observers;

use App\Models\Penerimaan;

class LaporanObserver
{
    /**
     * Handle the Penerimaan "created" event.
     */
    public function created(Penerimaan $penerimaan): void
    {
        //
    }

    /**
     * Handle the Penerimaan "updated" event.
     */
    public function updated(Penerimaan $penerimaan): void
    {
        //
    }

    /**
     * Handle the Penerimaan "deleted" event.
     */
    public function deleted(Penerimaan $penerimaan): void
    {
        //
    }

    /**
     * Handle the Penerimaan "restored" event.
     */
    public function restored(Penerimaan $penerimaan): void
    {
        //
    }

    /**
     * Handle the Penerimaan "force deleted" event.
     */
    public function forceDeleted(Penerimaan $penerimaan): void
    {
        //
    }
}
