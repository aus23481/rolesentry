<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Scrape_google extends Model
{
    //
    public $timestamps = false;
    public $table = "scrape_google";
    protected $fillable = [
        'title', 'company_name', 'first_google_result', 'first_google_result_text', 'opening_id'
    ];
}
