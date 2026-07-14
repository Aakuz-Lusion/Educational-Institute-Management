<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $fillable = ['homework_id', 'student_id', 'comment', 'file_path', 'submitted_at', 'grade'];

    public function homework()
    {
        return $this->belongsTo(Homework::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}