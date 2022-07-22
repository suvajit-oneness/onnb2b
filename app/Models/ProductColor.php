<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductColor extends Model
{
    protected $fillable = ['product_id', 'color_id', 'image'];


    public function product() {
        return $this->belongsTo('App\Models\Product', 'product_id', 'id');
    }

    public function color() {
        return $this->belongsTo('App\Models\Color', 'color_id', 'id');
    }
}
