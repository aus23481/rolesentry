<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rapid_approve_log extends Model
{
    ////id, field_changed, old_value, new_value, user_id, time_changed
    public $timestamps = false;
    protected $fillable = [
        'field_changed','old_value', 'new_value', 'user_id','time_changed'
    ]; 
}
