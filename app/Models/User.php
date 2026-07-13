<?php
namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // added role
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ---------- Relationships ----------

    public function studentProfile()
    {
        return $this->hasOne(Student::class);
    }

    public function teacherProfile()
    {
        return $this->hasOne(Teacher::class);
    }

    public function staffProfile()
    {
        return $this->hasOne(Staff::class);
    }

    // For teacher → sections (many-to-many)
    public function sections()
    {
        return $this->belongsToMany(Section::class, 'teacher_section', 'teacher_id', 'section_id');
    }

    // For homeworks created by this teacher
    public function homeworks()
    {
        return $this->hasMany(Homework::class, 'teacher_id');
    }

    // For invoices belonging to a student (user)
    public function invoices()
    {
        return $this->hasMany(FeeInvoice::class, 'student_id');
    }
}
