<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NextEmail extends Model
{
    //
        public $timestamps = false;
	public $table = "next_email";
        protected $fillable = [
            'id','opening_id'
        ];

	
        
}
