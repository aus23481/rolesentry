<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Opening extends Model
{
    //
   // public $timestamps = false;
    ///id, job_type_id, location_id, rolesentry_company_id, title, job_description
    protected $fillable = [
        'location_id','job_type_id', 'rolesentry_company_id','title', 'job_description', 'created_at', 'updated_at','job_description_url','manager_auto_detect','job_description_url_on_job_board','human_readable_job_title','human_readable_company_name'
    ];

    public function company()
    {
        return $this->belongsTo('App\Rolesentry_company', 'rolesentry_company_id');
        
    }

    public function hiring_manager_openings()
    {
        return $this->hasMany('App\Hiring_manager_opening');
        
    }
}
