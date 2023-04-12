<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'date',
        'from',
        'to',
        'repeat',
        'description',
        'is_important',
        'user_id',
        'is_completed',
    ];

    protected $dates = ['date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
