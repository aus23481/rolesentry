<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SavedSearchOpening extends Model
{
    //
        public $timestamps = false;
        protected $fillable = [
            'id','saved_search_id','opening_id'
        ];
}
