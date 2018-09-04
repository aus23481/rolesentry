<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Requested_company extends Model
{
    //
    protected $fillable = [
        'user_id','company_text_name', 'created_at','updated_at'
    ];
}
