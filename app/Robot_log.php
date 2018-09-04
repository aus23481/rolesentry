<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Robot_log extends Model
{
    //
    public $timestamps = false;
    protected $fillable = [
        'robot_company_id','robot_action_type_id',  'created_at','note'
    ];

    public function action_type()
    {
        return $this->hasOne('App\Robot_action_type');
    }
}
