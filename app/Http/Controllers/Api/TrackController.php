<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Track;
use App\Traits\ApiTrait;
use App\Http\Requests\StoreTrackRequest;
use App\Http\Resources\TracksResource;
use App\Traits\NotificationTrait;
use Notification;
use App\Notifications\TrackNotification;

class TrackController extends Controller
{
    use ApiTrait , NotificationTrait;

    public function addTrack(StoreTrackRequest $request){
        $user = auth()->user();
        $patient = User::where('type','patient')->where('user_id',$user->id)->first();

        if(!$patient){
            $msg = trans('home.patient_not_fount');
            return $this->failMsg($msg);
        }

        $track = $patient->tracks()->create($request->validated());
       
        $data = [
            'title' => 'family add new track',
            'body' => $track->date,
        ];
        $patient->notify(new TrackNotification($track));
        $this->sendNotification($patient,$data);

        // $msg = trans('home.added_successfully');
        // return $this->successMsg($msg);
        $data = [];
        $data['tracks'] = TracksResource::collection($patient->tracks);

        return $this->successReturn('',$data);
    }

    public function getTracks(){
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
        $data['tracks'] = TracksResource::collection($patient->tracks);

        return $this->successReturn('',$data);
        
    }
}
