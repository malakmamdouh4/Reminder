<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'result',
        'date',
        'time',
        'user_id',
        'exam_id',
        'question_id',
        'answer_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
