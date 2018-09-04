<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User_hidden_opening extends Model
{
    //
    public $timestamps = false;

    protected $fillable = [
        'user_id','opening_id'
    ];
}
