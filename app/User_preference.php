<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User_preference extends Model
{
    //
    protected $fillable = [
        'user_id','job_type_id','location_id','created_at','updated_at'
    ];
}
