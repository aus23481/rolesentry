<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Opening_tag extends Model
{
    //
     public $timestamps = false;
    
    protected $fillable = [
        'opening_id','tag_id'
    ];
}
