<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
//use Laravel\Sanctum\HasApiTokens;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
    ];

    public function getFullPhoneAttribute() {
        return '0' . $this->phone;
    }

    public function sendVerificationCode($has_changed_phone = 'false') {
        // $code = mt_rand(111111, 999999);
        $code         = '123456';
        $data['code'] = $code;

        // Mail::to($this->email)->send(new PassCode($code));

        $this->update(['code' => $code]);
    }

}
