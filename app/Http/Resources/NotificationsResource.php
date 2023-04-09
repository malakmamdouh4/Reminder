<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App;
use App\Models\Task;
use App\Models\Track;
use App\Models\History;
use App\Models\Reminder;
use App\Models\Memory;

class NotificationsResource extends JsonResource {

  public function toArray($request) {

    $data = $this->data;
    
    if(array_key_exists('task_id',$data)){
      $task = Task::find($data['task_id']);
      $message = trans('home.family_add_task').$task->title ;
    }
    elseif(array_key_exists('track_id',$data)){
      $track = Track::find($data['track_id']);
      $message = trans('home.family_add_track').$track->date ;
    }
    elseif(array_key_exists('history_id',$data)){
      $history = History::find($data['history_id']);
      $message = trans('home.family_add_history').$history->disease ;
    }
    elseif(array_key_exists('reminder_id',$data)){
      $reminder = Reminder::find($data['reminder_id']);
      $message = trans('home.family_add_reminder').$reminder->title ;
    }
    elseif(array_key_exists('memory_id',$data)){
      $memory = Memory::find($data['memory_id']);
      $message = trans('home.family_add_memory').$memory->title ;
    }
    else{
      $message = '';
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
