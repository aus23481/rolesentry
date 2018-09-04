<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Indeed_job_title extends Model
{
    //
    public $timestamps = false;
    protected $fillable = [
        'name'
    ];
}
