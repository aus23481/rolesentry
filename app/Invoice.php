<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    //
    protected $fillable = [
        'invoice_type_id', 'user_id', 'is_paid', 'created_at', 'updated_at'
    ];
}
