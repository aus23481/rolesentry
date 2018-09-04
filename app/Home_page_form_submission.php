<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Home_page_form_submission extends Model
{
    //
    protected $fillable = [
        'email', 'ip_address','created_at', 'updated_at', 'name', 'describe', 'phone', 'last_name'
    ];
}
