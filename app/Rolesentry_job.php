<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rolesentry_job extends Model
{
    //
        /**
     * Get the user that owns the order.
     */

    public function company()
    {
        return $this->belongsTo('App\Rolesentry_company', 'rolesentry_companies_id');
    }

    public function alerts()
    {
        return $this->hasMany('App\Rolesentry_alert');
    }

}
