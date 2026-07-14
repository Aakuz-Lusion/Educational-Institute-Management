<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = ['class_id', 'name'];

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function teachers()
    {
        return $this->belongsToMany(User::class, 'teacher_section', 'section_id', 'teacher_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function homeworks()
    {
        return $this->hasMany(Homework::class);
    }
    
}