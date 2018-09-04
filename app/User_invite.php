<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User_invite extends Model
{
    //
    public $timestamps = false;
    protected $fillable = [
        'inviter_user_id', 'invitee_user_id', 'created_at'
    ];

}
