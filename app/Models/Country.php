<?php

namespace App\Models;

use App\Traits\Uploadable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Spatie\Translatable\HasTranslations;

class Country extends Model
{

    use HasFactory, HasTranslations, Uploadable;

    protected $fillable = ['name', 'calling_code', 'flag'];

    public $translatable = ['name'];

    public function setFlagAttribute($value)
    {
        if (null != $value && is_file($value)) {
            $this->attributes['flag'] = $this->uploadFile($value, 'flags', true, 23, 17);
        }else{
            $this->attributes['flag'] = $value ;
        }
    }

    public function getFlagPathAttribute()
    {
        return asset('assets/uploads/flags/'  . $this->flag);
    }

    public static function boot()
    {
        parent::boot();

        static::deleted(function ($instance) {
            File::delete(public_path('assets/uploads/flags/' . $instance->flag));
        });
    }
}
