<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CandidateActivity extends Model
{
    //
   // public $timestamps = false;
	protected $table ="candidate_activity";	
	
        protected $fillable = [
            'candidate_id','candidate_activity_type_id', 'created_at', 'updated_at'
        ];
        public function candidate()
        {
            return $this->belongsTo('App\Candidate', 'candidate_id');
        }
        public function candidateActivityType()
        {
            return $this->belongsTo('App\CandidateActivityType', 'candidate_activity_type_id');
        }

        



}
