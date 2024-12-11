<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    //
    protected $fillable = [
        'name',
        'image',
        'qty',
        'price_buy',
        'price_sell',
    ];
}
