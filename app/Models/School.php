<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $table = 'schools';

    protected $fillable = [
        'school_id',
        'school_name',
        'department',
        'course',
        'semesters',
        'terms',
    ];

    protected $primaryKey = 'id';

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }
} 