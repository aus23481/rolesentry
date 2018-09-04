<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LockDetail extends Model
{
    //
   // public $timestamps = false;
        protected $fillable = [
            'id', 'lock_id', 'value' 
        ];

}
	

