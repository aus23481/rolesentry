<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job_board_scrape_log extends Model
{
    //
    public $timestamps = false;
    protected $fillable = [
        'job_board','job_terms', 'location_id','success', 'created_at'
    ];
}
