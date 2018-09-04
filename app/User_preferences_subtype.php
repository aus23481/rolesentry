<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User_preferences_subtype extends Model
{
    //
    public $timestamps = false;
    protected $fillable = [
        'user_id','location_id','job_subtype_id'
    ];

    public function jobsubtype()
    {
        return $this->belongsTo('App\Job_subtype', 'job_subtype_id');
        
    }
}
