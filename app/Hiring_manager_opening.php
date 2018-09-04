<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hiring_manager_opening extends Model
{
    //
    public $timestamps = false;
    protected $fillable = [
        'opening_id', 'hiring_manager_id','certainty'
    ];

    public function hiring_manager()
    {
        return $this->belongsTo('App\Hiring_manager', 'hiring_manager_id');
        
    }
}
