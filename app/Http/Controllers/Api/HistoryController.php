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

class HistoryController extends Controller
{
    use ApiTrait;

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

        $msg = trans('home.added_successfully');
        return $this->successMsg($msg);
    }

    public function updateMedicalHistory(UpdateHistoryRequest $request)
    {
        $history = History::find($request->history_id);
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

        $msg = trans('home.updated_successfully');
        return $this->successMsg($msg);
    }

    public function getHistory(){
        $user = auth()->user();
        $patient = User::where('type','patient')->where('user_id',$user->id)->first();

        if(!$patient){
            $msg = trans('home.patient_not_fount');
            return $this->failMsg($msg);
        }

        $data = [];
        $data['history'] = HistoriesResource::collection($patient->histories);

        return $this->successReturn('',$data);
        
    }

}
