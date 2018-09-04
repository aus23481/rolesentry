<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hiring_manager extends Model
{
    //
    public $timestamps = false;
    protected $fillable = [
        'name', 'title', 'linkedin_url', 'phone', 'email', 'intel', 'prospect_id', 'job_type_id', 'location_id', 'company_id'
    ];


    public function job_type()
    {
        return $this->belongsTo('App\Job_type', 'job_type_id');
        
    }
    
    public function company()
    {
        return $this->belongsTo('App\Rolesentry_company', 'company_id');
        
    }

    public function location()
    {
        return $this->belongsTo('App\Location', 'location_id');
        
    }


    public function prospect()
    {
        return $this->hasOne('App\Hiring_manager', 'prospect_id');
        
    }
    
}
