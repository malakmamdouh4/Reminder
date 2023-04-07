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
        $data['tasks'] = TasksResource::collection($patient->tasks);

        return $this->successReturn('',$data);
    }

    public function completeTask(Request $request){
        $user = auth()->user();
        $task = Task::where('id',$request->task_id)->where('user_id',$user->id)->first();
        
        if(!$task){
            $msg = trans('home.task_not_fount');
            return $this->failMsg($msg);
        }

        $data = [];
        $task->update(['is_completed' => 'true']);
        $data['tasks'] = TasksResource::collection($user->tasks);
        return $this->successReturn(trans('home.task_completed_successfully'),$data);
    }

}
