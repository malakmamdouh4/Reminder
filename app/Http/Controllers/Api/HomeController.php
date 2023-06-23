<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CountriesResource;
use App\Http\Resources\HomeResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\NotificationsResource;
use App\Http\Requests\UpdateLocationRequest;
use App\Models\Country;
use App\Models\User;
use App\Models\Section;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    use ApiTrait;
    public function home(Request $request){
       $lang = $request->header('lang') ?? 'ar';

       $data = [];
       $sections = Section::get();
       $data['sections'] = HomeResource::collection($sections);
       return $this->successReturn('',$data);
    }

    public function countries(Request $request)
    {
        $lang = $request->header('lang') ?? 'ar';

        $data = [];
        $countries = Country::get();
        $data['countries'] = CountriesResource::collection($countries);

        return $this->successReturn('',$data);
    }

    public function updateLocation(UpdateLocationRequest $request)
    {
        $user = auth()->user() ;
        $user->update($request->validated());

        $msg = trans('home.updated_successfully');
        return $this->successMsg($msg);
    }

    public function getLocation()
    {
        $user = auth()->user();

        if($user->type == 'family'){
            $patient = User::where('type','patient')->where('user_id',$user->id)->first();
        }elseif($user->type == 'patient'){
           $patient = auth()->user();
        }elseif($user->type == 'care_giver'){
            $patient = $user->parent?->branches()?->where('type','patient')->first();
        }

        if(!$patient){
            $msg = trans('home.patient_not_fount');
            return $this->failMsg($msg);
        }

        $data = [];
        $data['user'] = new UserResource($patient);
        return $this->successReturnLogin('', $data);
    }

    public function notifications(Request $request){
        $user = auth('api')->user();
        $user->unreadNotifications->markAsRead();
        $user->refresh();
        $notifications = $user->notifications()->orderBY('created_at','desc')->paginate($this->paginateNum());
        $data['notifications']  =  NotificationsResource::collection($notifications);
        $data['pagination']     =  $this->paginationModel($notifications);
        return $this->successReturn('',$data);
    }

    public function unseenNotificationsCount(Request $request){
        $user = auth('api')->user();
        $num_of_notifications  = $user->notifications()->where('read_at',null)->count();
        $data['num_not_seen_notifications']=$num_of_notifications;
        return $this->successReturn('',$data);
    }

}
