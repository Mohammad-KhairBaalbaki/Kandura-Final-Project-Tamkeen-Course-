<?php

namespace App\Observers;

use App\Events\DashboardNotificationRequested;
use App\Models\User;
use App\Notifications\User\UserAccountNotification;
use Illuminate\Support\Facades\Log;

class UserObserver
{
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
        if (!$user->wasChanged('is_active')) {

            return;
        }
        if (!$user->is_active) {
            //send notifications to admin when user is deactivated
            event(new DashboardNotificationRequested(
                'notify.users.deactivated',
                'User Deactivated',
                "User (#{$user->name}) has been deactivated by admin ",
                [
                    'type' => 'admin',
                    'event' => 'deactivated',
                    'user_id' => $user->id,
                    'url' => route('users.show', $user->id)
                ]
            ));

            //send notification to user when user is deactivated
            $user->notify(new UserAccountNotification(
                action: 'deactivated'
            ));
        } else {
            //send notification to user when user is activated
            $user->notify(new UserAccountNotification(
                action: 'activated'
            ));
        }



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
