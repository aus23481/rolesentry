<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CandidateActivityType extends Model
{
    //
   // public $timestamps = false;
	protected $table ="candidate_activity_type";	
	
        protected $fillable = [
            'id', 'name'
        ];

}
