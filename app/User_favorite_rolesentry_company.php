<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User_favorite_rolesentry_company extends Model
{
    //
    protected $fillable = [
        'user_id','rolesentry_company_id','created_at','updated_at'
    ];

    public function company()
    {
        return $this->belongsTo('App\Rolesentry_company', 'rolesentry_company_id');
    }

}
