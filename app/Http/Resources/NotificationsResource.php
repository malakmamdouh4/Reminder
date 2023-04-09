<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App;
use App\Models\Task;

class NotificationsResource extends JsonResource {

  public function toArray($request) {

    $data = $this->data;
    if(array_key_exists('task_id',$data)){
      $task = Task::find($data['task_id']);
      $message = trans('home.family_add_task').$task->title ;
    }else{
      $message = '' ;
    }
    $lang=App() -> getLocale();

    return [
      'id'            => $this->id,
      'message'       => $message,
      // 'type'          => $this->type,
      'created_at'    => $this->created_at->diffForHumans(),
    ];

  }
  
}
