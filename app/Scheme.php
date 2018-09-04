<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Scheme extends Model
{
    //
   // public $timestamps = false;
        protected $fillable = [
            'id', 'name', 'prospect_type_id' 
        ];
}
