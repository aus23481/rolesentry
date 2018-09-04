<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Indeed_listing extends Model
{
    //
    //public $timestamps = false;
    protected $fillable = [
        'indeed_company_id','indeed_location_id', 'indeed_job_title_id', 'created_at','updated_at','summary_text'
    ];
}
