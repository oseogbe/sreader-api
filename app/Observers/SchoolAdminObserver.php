<?php

namespace App\Observers;

use App\Models\SchoolAdmin;

class SchoolAdminObserver
{
    /**
     * Handle the SchoolAdmin "created" event.
     *
     * @param  \App\Models\SchoolAdmin  $schoolAdmin
     * @return void
     */
    public function created(SchoolAdmin $schoolAdmin)
    {
        //
    }

    /**
     * Handle the SchoolAdmin "updated" event.
     *
     * @param  \App\Models\SchoolAdmin  $schoolAdmin
     * @return void
     */
    public function updated(SchoolAdmin $schoolAdmin)
    {
        //
    }

    /**
     * Handle the SchoolAdmin "deleted" event.
     *
     * @param  \App\Models\SchoolAdmin  $schoolAdmin
     * @return void
     */
    public function deleted(SchoolAdmin $schoolAdmin)
    {
        //
    }

    /**
     * Handle the SchoolAdmin "restored" event.
     *
     * @param  \App\Models\SchoolAdmin  $schoolAdmin
     * @return void
     */
    public function restored(SchoolAdmin $schoolAdmin)
    {
        //
    }

    /**
     * Handle the SchoolAdmin "force deleted" event.
     *
     * @param  \App\Models\SchoolAdmin  $schoolAdmin
     * @return void
     */
    public function forceDeleted(SchoolAdmin $schoolAdmin)
    {
        //
    }
}
