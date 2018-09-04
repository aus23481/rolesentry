<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    //
    //public $timestamps = false;
    protected $fillable = [
        'name','email', 'message', 'created_at', 'updated_at'
    ];
}
