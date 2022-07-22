<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['cat_id', 'sub_cat_id', 'collection_id', 'name', 'short_desc', 'desc', 'price', 'offer_price', 'slug', 'meta_title', 'meta_desc', 'meta_keyword', 'style_no', 'image'];

    public function category() {
        return $this->belongsTo('App\Models\Category', 'cat_id', 'id');
    }

    public function subCategory() {
        return $this->belongsTo('App\Models\SubCategory', 'sub_cat_id', 'id');
    }

    public function collection() {
        return $this->belongsTo('App\Models\Collection', 'collection_id', 'id');
    }

    public function colorSize() {
        return $this->hasMany('App\Models\ProductColorSize', 'product_id', 'id');
    }
    public function color() {
        return $this->hasMany('App\Models\ProductColorSize' ,'color','id');
    }
    public function size() {
        return $this->hasMany('App\Models\Size',  'id');
    }
    public function saleDetails() {
        return $this->hasOne('App\Models\Sale', 'product_id', 'id');
    }
}
