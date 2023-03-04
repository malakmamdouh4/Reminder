<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ApiTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    use ApiTrait;

    public function register(RegisterRequest $request){
        $lang = $request->header('lang') ?? 'ar';

        $number         = $this->convert2english($request->phone);
        $phone          = $this->phoneValidate($number);
        $Unique         = $this->is_unique('phone', $phone);

        if ($Unique){
            $msg = trans('auth.phone_unique');
            return $this->failMsg($msg);
        }

        $request['password'] = Hash::make($request['password']);
        $request['remember_token']        = Str::random(10);
        $request['phone']                 = $phone;
        $request['status']                = 'pending';
        $request['complete_patient_info'] = 'false';
        $request['complete_giver_info']   = 'false';

        $user = User::create($request->except('password_confirmation'));
        $user->sendVerificationCode();

        $data['token'] = $user->createToken('Laravel Password Grant Client')->accessToken;
        $data['user'] = new UserResource($user);

        $msg = trans('auth.registered_successfully');
        return $this->successReturn($msg, $data);
    }

    public function login(LoginRequest $request){
        $number = $this->convert2english($request->phone);
        $phone  = $this->phoneValidate($number);

        if(Auth::attempt(['phone' => $phone, 'password' => $request->password])){
            $user = Auth::user();
            $data['token'] = $user->createToken('Laravel Password Grant Client')->accessToken;
            $data['user'] = new UserResource($user);

            return $this->successReturn('', $data);
        }else{
            $msg = trans('auth.wrong_credentials');
            return $this->failMsg($msg);
        }
    }

}
