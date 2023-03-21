<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Task;
use App\Traits\ApiTrait;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\TasksResource;

class TaskController extends Controller
{
    use ApiTrait;

    public function addTask(StoreTaskRequest $request){
        $user = auth()->user();
        $patient = User::where('type','patient')->where('user_id',$user->id)->first();

        if(!$patient){
            $msg = trans('home.patient_not_fount');
            return $this->failMsg($msg);
        }

        $task = $patient->tasks()->create($request->validated());
       
        $msg = trans('home.added_successfully');
        return $this->successMsg($msg);
    }

    public function getTasks(){
        $user = auth()->user();
        $patient = User::where('type','patient')->where('user_id',$user->id)->first();

        if(!$patient){
            $msg = trans('home.patient_not_fount');
            return $this->failMsg($msg);
        }

        $data = [];
        $data['tasks'] = TasksResource::collection($patient->tasks);

        return $this->successReturn('',$data);
        
    }

}
