<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User_favorite extends Model
{
    //
    public $timestamps = false;
    protected $fillable = [
        'favoriteable_item_id', 'table_id', 'user_id'
    ];

}
