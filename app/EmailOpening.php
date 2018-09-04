<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailOpening extends Model
{
    //
        public $timestamps = false;
	public $table = 'email_openings';
        protected $fillable = [
            'id','email_id','opening_id'
        ];
}
