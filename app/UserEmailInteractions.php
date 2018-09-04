<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserEmailInteractions extends Model
{
    protected $fillable = [
        'email_id', 'user_id', 'email_interaction_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    //
}
