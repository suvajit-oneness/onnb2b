<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DirectoryMom extends Model
{
    protected $table='directory_mom';
   
    public function user() {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
    public function store() {
        return $this->belongsTo('App\Models\Store', 'store_id', 'id');
    }
}
