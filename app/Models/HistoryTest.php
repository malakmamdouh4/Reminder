<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uploadable;

class HistoryTest extends Model
{
    use HasFactory , Uploadable;

    protected $fillable = [
        'test',
        'result',
        'date',
        'history_id',
        'image',
    ];

    protected $dates = ['date'];

    public function history()
    {
        return $this->belongsTo(History::class);
    }

    public function setImageAttribute($value)
    {
        if (null != $value && is_file($value)) {
            $this->attributes['image'] = $this->uploadFile($value, 'histories', true, 23, 17);
        }else{
            $this->attributes['image'] = $value ;
        }
    }

    public function getImagePathAttribute()
    {
        return asset('assets/uploads/histories/'  . $this->image);
    }

    public static function boot()
    {
        parent::boot();

        static::deleted(function ($instance) {
            File::delete(public_path('assets/uploads/histories/' . $instance->image));
        });
    }

}
