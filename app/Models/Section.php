<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image'
    ];

    public function getImagePathAttribute()
    {
        return asset('assets/uploads/sections/' . $this->image);
    }

    public static function boot() {
        parent::boot();
        self::deleted(function ($model) {
            $model->deleteFile($model->attributes['image'], 'sections');
        });
    }

}
