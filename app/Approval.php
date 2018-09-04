<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    //
   // public $timestamps = false;
        protected $fillable = [
            'id','email_subject','email_message','is_rejected','is_approved', 'created_at', 'updated_at', 'scheme_step_id', 'prospect_saved_search_progress_id' 
        ];
}
