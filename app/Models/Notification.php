<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['user_id', 'title', 'body', 'date','markAsRead','status'];

    public function users() {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
