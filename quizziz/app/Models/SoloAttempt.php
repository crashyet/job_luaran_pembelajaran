<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoloAttempt extends Model
{
    protected $fillable = [
        'quiz_id',
        'name',
        'class',
        'absent_no',
        'score',
        'correct_answers',
        'total_questions',
        'answers'
    ];

    protected $casts = [
        'answers' => 'array',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
