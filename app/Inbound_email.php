<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Inbound_email extends Model
{
    //
    public $timestamps = false;
    protected $fillable = [
        'message'
    ];
}
