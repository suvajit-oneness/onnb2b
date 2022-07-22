<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Distributor extends Model
{
    protected $fillable = ['user_id', 'area_id', 'store_name','bussiness_name', 'store_OCC_number','contact','whatsapp','email','address','area','state','city','pin'];

    public function user() {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function ProductDetails() {
        return $this->HasMany('App\Models\Product', 'store_id', 'id')->where('status', 1);
    }
}
