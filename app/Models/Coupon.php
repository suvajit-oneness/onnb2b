<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = ['name', 'coupon_code', 'type', 'amount', 'max_time_of_use', 'max_time_one_can_use', 'start_date', 'end_date'];
}
