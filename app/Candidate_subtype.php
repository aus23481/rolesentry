<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Candidate_subtype extends Model
{
    //
    public $timestamps = false;
    
    protected $fillable = [
        'candidate_id','job_subtype_id'
    ];
}
