<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Robot_company extends Model
{
    //
    protected $fillable = [
        'company_name','source_id', 'website', 'website_status_id', 'career_page', 'career_page_status_id', 'created_at','updated_at','robot_company_progression_status_id', 'location_id'
    ];

    public function positions()
    {
        return $this->hasMany('App\Positions_found_with_key_selector');
    }

    public function location()
    {
        return $this->belongsTo('App\Location');
    }
    
}
