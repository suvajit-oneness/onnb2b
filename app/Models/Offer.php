<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $fillable = ['title', 'is_current', 'image', 'pdf',  'start_date', 'end_date'];
}
