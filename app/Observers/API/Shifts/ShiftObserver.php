<?php

namespace App\Observers\API\Shifts;

use App\Shift;

class ShiftObserver
{
    /**
     * Handle the shifts "created" event.
     *
     * @param \App\Shift $shift
     * @return void
     */
    public function created (Shift $shift)
    {
        //
    }

    /**
     * Handle the shifts "creating" event.
     *
     * @param Shift $shift
     */
    public function creating (Shift $shift)
    {
        $shift->setCompanyId(); // set company id for creating shift
    }

    /**
     * Handle the shifts "updated" event.
     *
     * @param \App\Shift $shift
     * @return void
     */
    public function updated (Shift $shift)
    {
        //
    }

    /**
     * Handle the shifts "deleted" event.
     *
     * @param \App\Shift $shift
     * @return void
     */
    public function deleted (Shift $shift)
    {
        //
    }

    /**
     * Handle the shifts "restored" event.
     *
     * @param \App\Shift $shift
     * @return void
     */
    public function restored (Shift $shift)
    {
        //
    }

    /**
     * Handle the shifts "force deleted" event.
     *
     * @param \App\Shift $shift
     * @return void
     */
    public function forceDeleted (Shift $shift)
    {
        //
    }
}
