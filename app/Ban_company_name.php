<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ban_company_name extends Model
{
    //
   // public $timestamps = false;
    protected $fillable = [
        'term','created_at', 'updated_at'
    ];
}
