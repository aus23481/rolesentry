<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SchemeStepLockTypeDetail extends Model
{
    //
   // public $timestamps = false;
    ///id, job_type_id, location_id, rolesentry_company_id, title, job_description
    protected $fillable = [
        'id','scheme_step_lock_types_id', 'value'
    ];
}
