<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unsubscribe extends Model
{
    //
    protected $fillable = [
        'email','reason','created_at','updated_at'
    ];
}
