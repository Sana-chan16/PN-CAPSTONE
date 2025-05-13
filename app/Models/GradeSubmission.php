<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GradeSubmission extends Model
{
    use HasFactory;

    // If the table name is not the plural of the model name, you can specify it here
    // protected $table = 'grade_submissions';

    // Define fillable properties
    protected $fillable = [
        'school_id', 
        'class_id', 
        'semester', 
        'term', 
        'academic_year', 
        'subject_ids',
        'status',
        'remarks'
    ];

    // Cast subject_ids to array
    protected $casts = [
        'subject_ids' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Define any relationships (if needed)
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school_id', 'school_id');
    }

    public function classModel(): BelongsTo
    {
        return $this->belongsTo(ClassModel::class, 'class_id', 'class_id');
    }

    public function subjectGrades(): HasMany
    {
        return $this->hasMany(GradeSubmissionSubject::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'grade_submission_student', 'grade_submission_id', 'subject_id')
            ->withPivot('grade', 'status', 'submitted_at')
            ->withTimestamps();
    }

    public function students()
    {
        return $this->belongsToMany(PNUser::class, 'grade_submission_student', 'grade_submission_id', 'user_id')
            ->withPivot('subject_id', 'grade', 'status', 'submitted_at')
            ->withTimestamps();
    }

    public function getStudentGrade($userId, $subjectId)
    {
        return $this->students()
            ->wherePivot('user_id', $userId)
            ->wherePivot('subject_id', $subjectId)
            ->first();
    }

    public function scopeForStudent($query, $userId)
    {
        return $query->whereHas('students', function($q) use ($userId) {
            $q->where('grade_submission_student.user_id', $userId);
        });
    }

    public function scopeForClass($query, $classId)
    {
        return $query->where('class_id', $classId);
    }
}
