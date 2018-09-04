<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SavedSearchJobType extends Model
{
    //
        public $timestamps = false;
	public $table = 'saved_search_job_type';
        protected $fillable = [
            'saved_search_id','job_type_id'
        ];
}
