<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'customer_id',
        'amount',
        'invoice_number',
        'pdf_path',
        'status'
    ];

    //Relation with customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
