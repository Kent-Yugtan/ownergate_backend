<?php

namespace Modules\Auth\Observers;

use App\Models\User;

class UserObserverObserver
{
    /**
     * Handle the UserObserver "created" event.
     */
    public function created(User $userobserver): void
    {
        //
    }

    /**
     * Handle the UserObserver "updated" event.
     */
    public function updated(User $userobserver): void
    {
        //
    }

    /**
     * Handle the UserObserver "deleted" event.
     */
    public function deleted(User $userobserver): void
    {
        //
    }

    /**
     * Handle the UserObserver "restored" event.
     */
    public function restored(User $userobserver): void
    {
        //
    }

    /**
     * Handle the UserObserver "force deleted" event.
     */
    public function forceDeleted(User $userobserver): void
    {
        //
    }
}
