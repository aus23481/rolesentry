<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job_type_word extends Model
{
    //
        public $timestamps = false;
        protected $fillable = [
            'word','job_type_id'
        ];

        public function job_type_word_job_subtype()
        {
            return $this->hasMany('App\Job_type_word_job_subtype');
        }
}
