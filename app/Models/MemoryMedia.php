<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uploadable;
use Illuminate\Support\Facades\File;

class MemoryMedia extends Model
{
    use HasFactory , Uploadable;

    protected $fillable = [
        'memory_id',
        'file',
    ];

    public function memory()
    {
        return $this->belongsTo(Memory::class);
    }

    public function setFileAttribute($value)
    {
        if (null != $value && is_file($value)) {
            $this->attributes['file'] = $this->uploadFile($value, 'memories', true, 23, 17);
        }else{
            $this->attributes['file'] = $value ;
        }
    }

    public function getFilePathAttribute()
    {
        return asset('assets/uploads/memories/'  . $this->file);
    }

    public static function boot()
    {
        parent::boot();

        static::deleted(function ($instance) {
            File::delete(public_path('assets/uploads/memories/' . $instance->file));
        });
    }

}
