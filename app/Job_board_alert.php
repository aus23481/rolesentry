<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job_board_alert extends Model
{
    //
    public $timestamps = false;
    protected $fillable = [
        'job_description_on_company_page','job_descrpition_on_job_board_page', 'job_board', 'created_at'
    ];
}
