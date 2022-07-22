<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StartEndDay extends Model
{
    protected $fillable = [
        'user_id','start_location', 'end_location', 'start_lat','end_lat','start_lng','end_lng','start_date','end_date','start_time','end_time'
    ];

    public function users() {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
