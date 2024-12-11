<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    //
    protected $fillable = [
        'bill_id',
        'customer_name',
        'customer_tel',
    ];
}
