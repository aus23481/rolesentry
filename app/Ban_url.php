<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ban_url extends Model
{
    //
    protected $fillable = [
        'term','created_at', 'updated_at'
    ];
}
