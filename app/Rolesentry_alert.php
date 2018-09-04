<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rolesentry_alert extends Model
{
    //
    /**
     * Get the user that owns the order.
     */

    public function job()
    {
        return $this->belongsTo('App\Rolesentry_job', 'rolesentry_job_id');
    }

}
