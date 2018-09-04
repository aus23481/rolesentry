<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    //
   // public $timestamps = false;
	
        protected $fillable = [
            'first_name','last_name','job_type_id', 'location_id', 'created_at', 'updated_at', 'prospect_id'
        ];

        public function job_type()
        {
            return $this->belongsTo('App\Job_type', 'job_type_id');
            
        }

        public function location()
        {
            return $this->belongsTo('App\Location', 'location_id');
            
        }

        public function prospect()
        {
            return $this->hasOne('App\Candidate', 'prospect_id');
            
        }

        public function subtype()
        {
            return $this->hasMany('App\Candidate_subtype');
            
        }

        public function resume()
        {
            return $this->hasMany('App\Resume');
            
        }

}
