<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\SignUpWithSocialRequest;
use App\Http\Requests\ForgetPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\VerifyCodeRequest;
use App\Http\Resources\UserResource;
use App\Models\Authentication;
use App\Models\User;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $request['remember_token']        = Str::random(10);
        $request['phone']                 = $phone;
        $request['status']                = 'active';

        if($request['user_id'] && $user = User::find($request['user_id'])){
            $request['status']                         = 'pending';
            if ($request->type == 'patient'){
                $user->complete_patient_info           = 'true';
                $user->save();
                $request['complete_patient_info']      = 'true';
            }elseif($request->type == 'care_giver'){
                $user->complete_giver_info             = 'true';
                $user->save();
                $request['complete_giver_info']        = 'true';
            }
        }

        $user = User::create($request->except('password_confirmation'));
        $user->devices()->create(['device'=>$request['device_id']]);

        if($user->type == 'patient' || $user->type == 'care_giver'){
            $user->sendVerificationCode();
            $user = User::find($request['user_id']);
        }

        $data['token'] = $user->createToken('Laravel Password Grant Client')->accessToken;
        $data['user'] = new UserResource($user);

        $msg = trans('auth.registered_successfully');
        return $this->successReturnLogin($msg, $data);
    }

    public function login(LoginRequest $request){
        $number = $this->convert2english($request->phone);
        $phone  = $this->phoneValidate($number);

        if(!$request['social_id']) {
            if (Auth::attempt(['phone' => $phone, 'password' => $request->password])) {

                $user = Auth::user();
                $user->devices()->updateOrCreate(['device' => $request['device_id']]);

                $data['token'] = $user->createToken('Laravel Password Grant Client')->accessToken;
                $data['user'] = new UserResource($user);

                return $this->successReturnLogin('', $data);
            } else {
                $msg = trans('auth.wrong_credentials');
                return $this->failMsg($msg);
            }
        }else{
           return $this->loginWithSocial($request);
        }
    }

    public function userLogin(VerifyCodeRequest $request){
        $number         = $this->convert2english($request->phone);
        $phone          = $this->phoneValidate($number);

        $user = User::where('phone', $phone)->first();

        if (!$user) {
            $msg = trans('auth.user_not_found');
            return $this->failMsg($msg);
        }

        if($user->code != $request['code']){
            $msg = trans('auth.invalid_code');
            return $this->failMsg($msg);
        }

        $user->devices()->updateOrCreate(['device'=>$request['device_id']]);
        $data['token'] = $user->createToken('Laravel Password Grant Client')->accessToken;
        $data['user'] = new UserResource($user);
        return $this->successReturnLogin('', $data);
    }

    public function forgetPassword(ForgetPasswordRequest $request) {

        $number = $this->convert2english($request->phone);
        $phone  = $this->phoneValidate($number);

        $user = User::where('phone', $phone)->first();
        if (!$user) {
            $msg = trans('auth.user_not_found');
            return $this->failMsg($msg);
        }

        $user->sendVerificationCode();

        $msg = trans('auth.activation_code_sent');
        return $this->successMsg($msg);
    }

    public function resetPasswordActivation(VerifyCodeRequest $request){
        $number         = $this->convert2english($request->phone);
        $phone          = $this->phoneValidate($number);

        $user = User::where('phone', $phone)->first();

        if (!$user) {
            $msg = trans('auth.user_not_found');
            return $this->failMsg($msg);
        }

        if($user->code != $request['code']){
            $msg = trans('auth.invalid_code');
            return $this->failMsg($msg);
        }

        $msg=trans('auth.code_is_correct');
        return $this->successMsg($msg);
    }

    public function resetPassword(ResetPasswordRequest $request){
        $number         = $this->convert2english($request->phone);
        $phone          = $this->phoneValidate($number);

        $user = User::where('phone', $phone)->first();

        if (!$user) {
            $msg = trans('auth.user_not_found');
            return $this->failMsg($msg);
        }
        $user->update(['password'=>$request['password']]);

        $msg = trans('auth.password_changed');
        return $this->successMsg($msg);
    }

    public function logout(Request $request) {
        $token = $request->user()->token();
        $user = auth()->user();
        $user->devices()->where(['device'=>$request['device_id']])->delete();

        $token->revoke();

        $msg = trans('auth.logout_success');
        return $this->successMsg($msg);
    }

    public function loginWithSocial(Request $request){
        $data = [];
        $lang = $request->header('lang');
        if($authentication = Authentication::with('user')->where('uid','=',$request->social_id)->first()){
            $authentication->update([
                'username'=>$request->name ?? '',
                'email'=>$request->email??'',
            ]);
            if($user = $authentication->user){
                $user->update([
                    'name'=>$request->name ?? '',
                    'email'=>$request->email??'',
                    'phone'=>$request->phone??'',
                    'status'=>'active',
                    'active'=>1,
                    'completed_info'=>'true',
                ]);
//                $data['registered_social'] = false;
//                $data['phone_registered']  = ($user->phone != null )? true : false;
            }
        }else{
            if($request->email && $user = User::where('email','=',$request->email)->first()){
                $user->update(['name'=>$request->name ?? '' ]);
                $authentication = Authentication::create([
                    'uid'      => $request->social_id,
                    'user_id'  => $user->id,
                    'username' => $request->name,
                    'email'    => $request->email,
                ]);
//                $data['phone_registered']  = ( $user->phone != null )? true : false;
//                $data['registered_social'] = true;
            }else{
                $user=User::create([
                    'name'=>$request->name ?? '',
                    'email'=>$request->email??'',
                    'phone'=>$request->phone??'',
                    'status'=>'active',
                    'active'=>1,
                    'completed_info'=>'true',
                    'type' => 'family',
                ]);
                $user->code=$user->sendVerificationCode();
                $user->update();
                $authentication = Authentication::create([
                    'uid'      => $request->social_id,
                    'user_id'  => $user->id,
                    'username' => $request->name,
                    'email'    => $request->email,
                ]);
//                $data['phone_registered']  = ( $user->phone != null )? true : false;
//                $data['registered_social'] = false;
            }
        }

        $user->devices()->updateOrCreate(['device' => $request['device_id']]);
        $is_registered = false;
        if( $user->name ){
            $is_registered = true;
        }else{
            $is_registered = false;
        }
        $data['token'] = $user->createToken('Laravel Password Grant Client')->accessToken;
        $data['user'] = new UserResource($user);
        $data['user']['is_registered'] = $is_registered;

        return $this->successReturn('', $data);
    }


}
