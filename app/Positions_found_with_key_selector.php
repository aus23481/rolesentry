<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Positions_found_with_key_selector extends Model
{
    //
    public $timestamps = false;

    protected $fillable = [
        'position_title','robot_company_id'
    ];

    
}
