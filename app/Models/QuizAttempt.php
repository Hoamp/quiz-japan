<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ordering_id',
        'attempt_date',
        'correct_answers',
        'incorrect_answers'
    ];

    public function ordering() {
        return $this->belongsTo(Ordering::class);
    }
}
