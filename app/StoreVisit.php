<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreVisit extends Model
{
    protected $fillable = [
        'user_id','date', 'time', 'type','comment','location','lat','lng'
    ];

    public function users() {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
