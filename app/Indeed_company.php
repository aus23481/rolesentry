<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Indeed_company extends Model
{
    //
    public $timestamps = false;
    protected $fillable = [
        'name','url', 'location_id'
    ];
}
