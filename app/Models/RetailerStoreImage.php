<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RetailerStoreImage extends Model
{
    protected $table ='retailer_store_image';
    public function store() {
        return $this->belongsTo('App\Models\User', 'store_id', 'id');
    }
}
