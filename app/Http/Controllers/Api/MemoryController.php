<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MemoryMedia;
use App\Models\Memory;
use App\Traits\ApiTrait;
use App\Http\Requests\StoreMemoryRequest;
use App\Http\Resources\MemoriesResource;
use App\Http\Resources\MemoryResource;

class MemoryController extends Controller
{
    use ApiTrait;

    public function addMemory(StoreMemoryRequest $request){
        $user = auth()->user();
        $patient = User::where('type','patient')->where('user_id',$user->id)->first();

        if(!$patient){
            $msg = trans('home.patient_not_fount');
            return $this->failMsg($msg);
        }

        $memory = $patient->memories()->create($request->except('media'));
        if(isset($request['media'] )){
           foreach($request['media'] as $media){
                MemoryMedia::create([
                    'file' => $media,
                    'memory_id' => $memory->id,
                ]);
           }
        }
       
        $msg = trans('home.added_successfully');
        return $this->successMsg($msg);
    }

    public function getMemories(){
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
        $general_memories = $patient->memories()->where('type','general')->get();
        $occasional_memories = $patient->memories()->where('type','occasional')->get();
        $data['general'] = MemoriesResource::collection($general_memories);
        $data['occasional'] = MemoriesResource::collection($occasional_memories);

        return $this->successReturn('',$data);
        
    }

    public function getMemory(Request $request){
        $memory = Memory::find($request->memory_id);
       
        if(!$memory){
            $msg = trans('home.memory_not_fount');
            return $this->failMsg($msg);
        }

        $data['memory'] = new MemoryResource($memory);
        return $this->successReturn('',$data);
    }
}
