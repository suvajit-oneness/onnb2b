<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductColorSize extends Model
{
    protected $fillable = ['product_id', 'color', 'size', 'stock', 'code', 'price', 'offer_price'];

    public function product() {
        return $this->belongsTo('App\Models\Product', 'product_id', 'id');
    }

    public function colorDetails() {
        return $this->belongsTo('App\Models\Color', 'color', 'id');
    }

    public function sizeDetails() {
        return $this->belongsTo('App\Models\Size', 'size', 'id');
    }
}
