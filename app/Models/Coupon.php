<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $casts = [
        'value' => 'float',
        'cart_value' => 'float',
        'expiry_date' => 'date',
        'is_active' => 'boolean',
    ];
}