<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Owler_company extends Model
{
    protected $fillable = [
        'company_name','company_description', 'link_to_full_company_profile', 'created_at','updated_at','worked', 'location_id'
    ];
}
