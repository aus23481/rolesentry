<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Startupslist_location extends Model
{
    //
    public $timestamps = false;
    protected $fillable = [
        'name', 'url'
    ];
}
