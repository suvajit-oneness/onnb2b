<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserNoorderreason extends Model
{
    public function user() {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
    public function store() {
        return $this->belongsTo('App\Models\Store', 'store_id', 'id');
    }
}
