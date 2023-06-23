<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Reminder;
use App\Traits\ApiTrait;
use App\Http\Requests\StoreReminderRequest;
use App\Http\Resources\RemindersResource;
use App\Traits\NotificationTrait;
use Notification;
use App\Notifications\ReminderNotification;

class ReminderController extends Controller
{
    use ApiTrait , NotificationTrait;

    public function addReminder(StoreReminderRequest $request){
        $user = auth()->user();
        $patient = User::where('type','patient')->where('user_id',$user->id)->first();

        if(!$patient){
            $msg = trans('home.patient_not_fount');
            return $this->failMsg($msg);
        }

        $reminder = $patient->reminders()->create($request->validated());

        $data = [
            'title' => $reminder->title,
            'body' => $reminder->dose,
        ];
        $patient->notify(new ReminderNotification($reminder));
        $this->sendNotification($patient,$data);

        // $msg = trans('home.added_successfully');
        // return $this->successMsg($msg);

        $data = [];
        $data['reminders'] = RemindersResource::collection($patient->reminders);

        return $this->successReturn('',$data);
    }

    public function getReminders(){
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
        $data['reminders'] = RemindersResource::collection($patient->reminders);

        return $this->successReturn('',$data);
    }

    public function updateReminder(Request $request)
    {
        $reminder = Reminder::find($request->reminder_id);
        $reminder->update($request->all());
        $user = auth()->user();
        $patient = User::where('type','patient')->where('user_id',$user->id)->first();

        if(!$patient){
            $msg = trans('home.patient_not_fount');
            return $this->failMsg($msg);
        }
        $data = [];
        $data['reminders'] = RemindersResource::collection($patient->reminders);

        return $this->successReturn('',$data);
    }


    public function deleteReminder(Request $request)
    {
        $reminder = Reminder::find($request->reminder_id)->delete();

        $msg = trans('home.deleted_successfully');
        return $this->successMsg($msg);
    }

}
