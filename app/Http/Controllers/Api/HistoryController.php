<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreHistoryRequest;
use App\Http\Requests\UpdateHistoryRequest;
use App\Http\Resources\HistoriesResource;
use App\Models\History;
use App\Models\HistoryTest;
use App\Models\User;
use App\Traits\ApiTrait;
use App\Traits\NotificationTrait;
use Notification;
use App\Notifications\HistoryNotification;

class HistoryController extends Controller
{
    use ApiTrait , NotificationTrait;

    public function addMedicalHistory(StoreHistoryRequest $request)
    {
        $user = auth()->user();
        $patient = User::where('type','patient')->where('user_id',$user->id)->first();

        if(!$patient){
            $msg = trans('home.patient_not_fount');
            return $this->failMsg($msg);
        }
        $request['user_id'] = $patient->id ; 
        $history = History::create($request->except('tests'));

        if (isset($request['tests'])) {
            foreach (json_decode($request['tests']) as $t) {
                HistoryTest::create([
                    'test' => $t->test,
                    'result' => $t->result,
                    'date' => $t->date,
                    'history_id' => $history->id,
                ]);
            }
        }

        $data = [
            'title' => $history->disease,
            'body' => $history->diagnose,
        ];
        $patient->notify(new HistoryNotification($history));
        $this->sendNotification($patient,$data);

        // $msg = trans('home.added_successfully');
        // return $this->successMsg($msg);
        $data = [];
        $data['history'] = HistoriesResource::collection($patient->histories);

        return $this->successReturn('',$data);
    }

    public function updateMedicalHistory(UpdateHistoryRequest $request)
    {
        $user = auth()->user();
        $history = History::find($request->history_id);
        
        $patient = User::where('type','patient')->where('user_id',$user->id)->first();
        if(!$patient){
            $msg = trans('home.patient_not_fount');
            return $this->failMsg($msg);
        }
        
        $history->update($request->except('tests'));
        $history->tests()->delete();

        if (isset($request['tests'])) {
            foreach (json_decode($request['tests']) as $t) {
                HistoryTest::create([
                    'test' => $t->test,
                    'result' => $t->result,
                    'date' => $t->date,
                    'history_id' => $history->id,
                ]);
            }
        }

        $data = [
            'title' => $history->disease,
            'body' => $history->diagnose,
        ];
        $patient->notify(new HistoryNotification($history));
        $this->sendNotification($patient,$data);

        // $msg = trans('home.updated_successfully');
        // return $this->successMsg($msg);
        $data = [];
        $data['history'] = HistoriesResource::collection($patient->histories);

        return $this->successReturn('',$data);
    }

    public function getHistory(){
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
        $data['history'] = HistoriesResource::collection($patient->histories);

        return $this->successReturn('',$data);
        
    }

}
