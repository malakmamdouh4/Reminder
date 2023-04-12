<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uploadable;
use Illuminate\Support\Facades\File;

class Track extends Model
{
    use HasFactory , Uploadable;

    protected $fillable = [
        'date',
        'time',
        'user_id',
        'file',
    ];

    protected $dates = ['date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function setFileAttribute($value)
    {
        if (null != $value && is_file($value)) {
            $this->attributes['file'] = $this->uploadFile($value, 'tracks', true, 23, 17);
        }else{
            $this->attributes['file'] = $value ;
        }
    }

    public function getFilePathAttribute()
    {
        return asset('assets/uploads/tracks/'  . $this->file);
    }

    public static function boot()
    {
        parent::boot();

        static::deleted(function ($instance) {
            File::delete(public_path('assets/uploads/tracks/' . $instance->file));
        });
    }

}
