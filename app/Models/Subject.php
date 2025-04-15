<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'offer_code',
        'subject_name',
        'instructor',
        'schedule',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
