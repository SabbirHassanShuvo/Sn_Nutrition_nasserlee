<?php

namespace App\Observers;

use App\Models\User;
use Exception;

class UserObserver
{
    /**
     * Handle the User "saving" event.
     */
    public function saving(User $user): void
    {
        // Enforce max 2 super_admin rule
        if ($user->isDirty('role') && $user->role === 'super_admin') {
            // Count existing super admins, excluding the current user being updated
            $query = User::where('role', 'super_admin');
            if ($user->exists) {
                $query->where('id', '!=', $user->id);
            }
            
            if ($query->count() >= 2) {
                throw new Exception('Cannot create more than 2 Super Admins.');
            }
        }
    }

    /**
     * Handle the User "saved" event.
     */
    public function saved(User $user): void
    {
        // Sync Spatie role with the users.role column
        if ($user->isDirty('role') || $user->wasRecentlyCreated) {
            if ($user->role) {
                $user->syncRoles([$user->role]);
            }
        }
    }

    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        //
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
