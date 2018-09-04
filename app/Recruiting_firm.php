<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recruiting_firm extends Model
{
    //
    public $timestamps = false;
    protected $fillable = [
        'name', 'email'
    ];
}
