<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SavedSearch extends Model
{
    //
        public $timestamps = true;
	    public $table = "saved_search";
        protected $fillable = [
            'id','user_id','term','time_to_send','is_daily','is_instant','email_subject','email_body','prospecting_type_id', 'needs_approval', 'name', 'created_at', 'updated_at'
        ];

        public function job_type()
        {
            return $this->hasMany('App\SavedSearchJobType', 'App\Job_type');
        }
        
}
