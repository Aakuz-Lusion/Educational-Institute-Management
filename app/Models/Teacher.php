<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Teacher extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'teachers';

    protected $fillable = [
        'user_id',
        'phone',
        'address',
        'subject',
        'grade',
        'priority',
        'days',
        'periods',
        'is_available',
        'unavailable_date',
        'unavailable_reason',
        'is_active'
    ];

    protected $casts = [
        'days' => 'array',
        'periods' => 'array',
        'is_available' => 'boolean',
        'is_active' => 'boolean',
        'unavailable_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}