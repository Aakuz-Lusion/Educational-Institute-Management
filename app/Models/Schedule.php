<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'day',
        'grade',
        'period_id',
        'subject',
        'teacher_id',
        'teacher_name'
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}