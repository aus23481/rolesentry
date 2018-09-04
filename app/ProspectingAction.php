<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProspectingAction extends Model
{
    //
    public $timestamps = true;
    protected $fillable = [
	'prospecting_action_type_id', 'prospect_id', 'subject', 'message','prospect_saved_search_progresses_id','saved_search_id', 'scheme_step_id','created_at'];
    
}
