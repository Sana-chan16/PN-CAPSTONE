<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'school_id',
        'class_id',
        'student_id',
        'date_of_birth',
        'gender',
        'address',
        'phone_number',
        'parent_name',
        'parent_contact'
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function classes()
    {
        return $this->belongsToMany(SchoolClass::class, 'class_student', 'student_id', 'class_id');
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
} 