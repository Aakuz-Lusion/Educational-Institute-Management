<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeePayment extends Model
{
    protected $fillable = ['invoice_id', 'amount', 'payment_date', 'payment_method'];
}