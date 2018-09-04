<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favoriteable_item extends Model
{
    //
    public $timestamps = false;
    protected $fillable = [
        'name', 'table_name'
    ];
}
