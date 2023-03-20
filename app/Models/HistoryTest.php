<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'test',
        'result',
        'date',
        'history_id',
    ];

    public function history()
    {
        return $this->belongsTo(History::class);
    }

}
