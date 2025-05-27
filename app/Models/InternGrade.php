<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternGrade extends Model
{
    use HasFactory;

    protected $table = 'intern_grades';

    protected $fillable = [
        'intern_id',
        'school_id',
        'class_id',
        'subject_id',
        'grade',
        'remarks',
        'status',
        'created_by',
        'updated_by'
    ];

    // Relationships
    public function intern()
    {
        return $this->belongsTo(PNUser::class, 'intern_id', 'user_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id', 'school_id');
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id', 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(PNUser::class, 'created_by', 'user_id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(PNUser::class, 'updated_by', 'user_id');
    }
} 