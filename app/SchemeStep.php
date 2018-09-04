<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SchemeStep extends Model
{
    //
   // public $timestamps = false;
        protected $fillable = [
            'id','saved_search_id', 'created_at', 'updated_at', 'email_body', 'email_subject', 'scheme_steps_wait_id' 
        ];

}
	

