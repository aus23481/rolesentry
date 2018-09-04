<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProspectSavedSearchProgress extends Model
{
    //
   // public $timestamps = false;
        protected $fillable = [
            'id','prospect_id', 'created_at', 'updated_at', 'saved_search_id', 'current_scheme_step_id', 'is_finished', 'max_age', 'expired', 'opening_id' 
        ];

}
	

