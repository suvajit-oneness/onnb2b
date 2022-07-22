<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Color;
use App\Models\Size;

class CartDistributor extends Model
{
    protected $fillable = ['id', 'user_id', 'device_id', 'ip', 'product_id', 'product_name','product_style_no' ,'product_image', 'product_slug', 'product_variation_id', 'price', 'offer_price', 'color', 'size', 'qty', 'coupon_code_id', 'status'];

    protected $table = 'carts_distributors';

    public function colorDetails()
    {
        return $this->belongsTo(Color::class, 'color', 'id');
    }

    public function sizeDetails()
    {
        return $this->belongsTo(Size::class, 'size', 'id');
    }
}
