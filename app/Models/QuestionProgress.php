<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionProgress extends Model
{
    public const MAX_LEVEL = 5;

    protected $table = 'question_progress';

    protected $fillable = [
        'user_id',
        'quiz_question_id',
        'level',
        'correct_count',
        'attempts',
        'last_answered_at',
    ];

    protected $casts = [
        'last_answered_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(QuizQuestion::class, 'quiz_question_id');
    }
}
