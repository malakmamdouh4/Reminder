<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Track;
use App\Traits\ApiTrait;
use App\Http\Requests\StoreTrackRequest;
use App\Http\Resources\TracksResource;

class TrackController extends Controller
{
    use ApiTrait;

    public function addTrack(StoreTrackRequest $request){
        $user = auth()->user();
        $patient = User::where('type','patient')->where('user_id',$user->id)->first();

        if(!$patient){
            $msg = trans('home.patient_not_fount');
            return $this->failMsg($msg);
        }

        $track = $patient->tracks()->create($request->validated());
       
        $msg = trans('home.added_successfully');
        return $this->successMsg($msg);
    }

    public function getTracks(){
        $user = auth()->user();
        $patient = User::where('type','patient')->where('user_id',$user->id)->first();

        if(!$patient){
            $msg = trans('home.patient_not_fount');
            return $this->failMsg($msg);
        }

        $data = [];
        $data['tracks'] = TracksResource::collection($patient->tracks);

        return $this->successReturn('',$data);
        
    }
}
