<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeInvoice extends Model
{
    protected $fillable = ['student_id', 'fee_structure_id', 'amount', 'due_date', 'status'];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function feeStructure()
    {
        return $this->belongsTo(FeeStructure::class);
    }

    public function payments()
    {
        return $this->hasMany(FeePayment::class);
    }
}