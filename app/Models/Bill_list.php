<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bill_list extends Model
{
    //
    protected $fillable = [
        'bill_id',
        'name',
        'qty',
        'price',
    ];
}
