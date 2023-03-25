<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
//use Laravel\Sanctum\HasApiTokens;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'country_key',
        'gender',
        'date_birth',
        'lat',
        'long',
        'address',
        'status',
        'code',
        'email_verified_at',
        'phone_verified_at',
        'lang',
        'complete_patient_info',
        'complete_giver_info',
        'type',
        'user_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
    ];

    public function getFullPhoneAttribute() {
        return '0' . $this->phone;
    }

    public function setPasswordAttribute($value) {
        if ($value != null) {
            $this->attributes['password'] = Hash::make($value);
        }
    }

    public function sendVerificationCode($has_changed_phone = 'false') {
        // $code = mt_rand(111111, 999999);
        $code         = '123456';
        $data['code'] = $code;
        $this->update(['code' => $code]);
    }

    public function isFather()
    {
        return is_null($this->attributes['user_id']);
    }

    protected $appends = ['is_father'];

    public function getIsFatherAttribute()
    {
        return is_null($this->attributes['user_id']);
    }

    public function parent()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function branches()
    {
        return $this->hasMany(User::class);
    }

    public function histories()
    {
        return $this->hasMany(History::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function reminders()
    {
        return $this->hasMany(Reminder::class);
    }

    public function tracks()
    {
        return $this->hasMany(Track::class);
    }

}
