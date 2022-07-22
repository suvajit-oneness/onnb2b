<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDistributor extends Model
{
	
    protected $fillable = [ 'order_no', 'ip', 'user_id', 'fname', 'lname', 'email', 'mobile', 'alt_mobile', 'billing_address_id', 'billing_address', 'billing_landmark', 'billing_country', 'billing_state', 'billing_city', 'billing_pin', 'shippingSameAsBilling', 'shipping_address_id', 'shipping_address', 'shipping_landmark', 'shipping_country', 'shipping_state', 'shipping_city', 'shipping_pin', 'amount', 'shipping_charges', 'shipping_method', 'tax_amount', 'discount_amount', 'coupon_code_id', 'final_amount', 'gst_no', 'payment_method', 'is_paid', 'txn_id', 'status', 'order_lat', 'order_lng', 'comment'];

    protected $table = 'orders_distributors';

    public function orderProducts() {
        return $this->hasMany('App\Models\OrderProductDistributor', 'order_id', 'id');
    }

    public function users() {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
