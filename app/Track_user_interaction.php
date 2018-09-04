<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Track_user_interaction extends Model
{
    //
    public $timestamps = false;
    protected $fillable = [
        'user_id','interaction_id','created_at'
    ];
    
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

}
