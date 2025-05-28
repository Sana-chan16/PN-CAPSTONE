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
        'company_name',
        'ict_learning_competency',
        'twenty_first_century_skills',
        'expected_outputs_deliverables',
        'final_grade',
        'status',
        'remarks',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'ict_learning_competency' => 'integer',
        'twenty_first_century_skills' => 'integer',
        'expected_outputs_deliverables' => 'integer',
        'final_grade' => 'float'
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

    public function createdBy()
    {
        return $this->belongsTo(PNUser::class, 'created_by', 'user_id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(PNUser::class, 'updated_by', 'user_id');
    }

    // Calculate final grade based on weighted criteria
    public function calculateFinalGrade()
    {
        // Calculate the final grade as the average of the three grades
        $this->final_grade = round(($this->ict_learning_competency + $this->twenty_first_century_skills + $this->expected_outputs_deliverables) / 3, 1);
        // Set the status based on the final grade
        $this->status = $this->calculateStatus();
        return $this->final_grade;
    }

    public function calculateStatus()
    {
        if ($this->final_grade === null) {
             return null;
        }
        // Convert (numeric) final_grade (1–4) into a status string (e.g. "Fully Achieved" if final_grade is 1, "Partially Achieved" if 2, "Barely Achieved" if 3, "No Achievement" if 4).
        switch (round($this->final_grade)) {
             case 1:
                 return "Fully Achieved";
             case 2:
                 return "Partially Achieved";
             case 3:
                 return "Barely Achieved";
             case 4:
                 return "No Achievement";
             default:
                 return "Unknown";
        }
    }
} 