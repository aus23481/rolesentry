<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job_type_word_job_subtype extends Model
{
    //
    public $timestamps = false;
    protected $fillable = [
        'job_type_word_id', 'job_subtype_id'
    ];
}
