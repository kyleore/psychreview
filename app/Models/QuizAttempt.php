<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    protected $fillable = ['user_id', 'session_id', 'score', 'total'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
