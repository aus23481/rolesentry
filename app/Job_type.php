<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job_type extends Model
{
    //

    public function words()
    {
        return $this->hasMany('App\Job_type_word');
    }

    public function subtypes()
    {
        return $this->hasMany('App\Job_subtype');
    }
}
