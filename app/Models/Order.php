<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['ip', 'user_id', 'fname', 'lname', 'email', 'mobile', 'alt_mobile', 'billing_address_id', 'billing_address', 'billing_landmark', 'billing_country', 'billing_state', 'billing_city', 'billing_pin', 'shipping_address_id', 'shipping_address', 'shipping_landmark', 'shipping_country', 'shipping_state', 'shipping_city', 'shipping_pin', 'amount', 'tax_amount', 'discount_amount', 'coupon_code_id', 'final_amount', 'gst_no', 'is_paid', 'txn_id'];

    public function orderProducts() {
        return $this->hasMany('App\Models\OrderProduct', 'order_id', 'id');
    }
    public function users() {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
    public function stores() {
        return $this->belongsTo('App\Models\Store', 'store_id', 'id');
    }

    public function colorDetails() {
        return $this->belongsTo('App\Models\Color', 'color', 'id');
    }
}
