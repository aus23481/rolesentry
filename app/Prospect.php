<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Prospect extends Model
{
    //
    public $timestamps = false;
    protected $fillable = [
        'type_id', 'reachable','last_bothered'
    ];
}
