<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location_autoselect extends Model
{
    //
    public $timestamps = false;
    protected $fillable = [
        'closest_location_id', 'autoselect_location_id'
    ];

}
