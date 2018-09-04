<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SavedSearchLocation extends Model
{
    //
        public $timestamps = false;
	public $table = "saved_search_location";
        protected $fillable = [
            'saved_search_id','location_id'
        ];
}
