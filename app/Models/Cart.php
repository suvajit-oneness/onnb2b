<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Color;
use App\Models\Size;

class Cart extends Model
{
    protected $fillable = ['ip','user_id', 'product_id', 'product_name','product_style_no' ,'product_image', 'product_slug', 'product_variation_id', 'price', 'offer_price', 'qty'];

    public function colorDetails()
    {
        return $this->belongsTo(Color::class, 'color', 'id');
    }

    public function sizeDetails()
    {
        return $this->belongsTo(Size::class, 'size', 'id');
    }
}
