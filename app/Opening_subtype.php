<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Opening_subtype extends Model
{
    //
    public $timestamps = false;
    protected $fillable = [
        'opening_id','subtype_id'
    ];

}
