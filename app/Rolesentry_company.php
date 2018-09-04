<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rolesentry_company extends Model
{
    //
    protected $fillable = [
        'name', 'angellist_url', 'career_page_url', 'xpath', 'first_scrape','created_at', 'updated_at', 'location_id', 'user_id'
    ];

    public function jobs()
    {
        return $this->hasMany('App\Rolesentry_job','rolesentry_companies_id');
    }

    public function alerts()
    {
        return $this->hasManyThrough(
            'App\Rolesentry_alert',
            'App\Rolesentry_job',
             'rolesentry_companies_id',
             'rolesentry_job_id'           
        );
    }

}
