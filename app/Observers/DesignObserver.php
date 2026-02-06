<?php

namespace App\Observers;

use App\Events\DashboardNotificationRequested;
use App\Models\Design;

class DesignObserver
{
    /**
     * Handle the Design "created" event.
     */
    public function created(Design $design): void
    {
        //
        // send notifications to admin when design is created
        event(new DashboardNotificationRequested(
            'notify.designs.created',
            'New Design Created',
            " New Design Added by user #{$design->user->name} ",
            [
                'type' => 'admin',
                'event' => 'created',
                'design_id' => $design->id,
                'url' => route('designs.show', $design->id),
            ]
        ));

    }

    /**
     * Handle the Design "updated" event.
     */
    public function updated(Design $design): void
    {
        //
        // send notifications to admin when design is created
        event(new DashboardNotificationRequested(
            'notify.designs.updated',
            "Design #{$design->id} Updated",
            " Design #{$design->id} Updated by user #{$design->user->name} ",
            [
                'type' => 'admin',
                'event' => 'updated',
                'design_id' => $design->id,
                'url' => route('designs.show', $design->id),
            ]
        ));

    }

    /**
     * Handle the Design "deleted" event.
     */
    public function deleted(Design $design): void
    {
        //
    }

    /**
     * Handle the Design "restored" event.
     */
    public function restored(Design $design): void
    {
        //
    }

    /**
     * Handle the Design "force deleted" event.
     */
    public function forceDeleted(Design $design): void
    {
        //
    }
}
