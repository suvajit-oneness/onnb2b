<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceRetailer extends Model
{
    protected $table ='invoice_retailer';
    public function store() {
        return $this->belongsTo('App\Models\User', 'retailer_id', 'id');
    }
}
