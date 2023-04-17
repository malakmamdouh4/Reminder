<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\QuestionsResource;
use App\Http\Resources\ExamsResource;
use App\Models\Question;
use App\Models\Exam;
use App\Models\User;
use App\Traits\ApiTrait;

class ExamController extends Controller
{
    use ApiTrait;

    public function getExam(){
      $user = auth()->user();
      $exams = Question::where('available','true')->get();

      $data = [];
      $data['exams'] = QuestionsResource::collection($exams);
      return $this->successReturn('',$data);
    }

    public function doExam(Request $request){
      $user = auth()->user();

      if (isset($request['answer'])) {
        $result = 0 ; 

        foreach (json_decode($request['answer']) as $test) {
          $question = Question::find($test->question_id);
          $answer = $question->answers()?->where('status','true')->first()?->id ; 
          
          if($answer == $test->answer_id){
            $result += 10 ;
          }

        }
          $user_result = Exam::create([
              'result' => $result,
              'time' => date("h:i:s"),
              'date' => date('Y-m-d'),
              'user_id' => $user->id,
          ]);

          return $this->successReturn('',$user_result->result);
      }else{
        $msg = trans('home.should_answer_first');
        return $this->failMsg($msg);
      }

    }

    public function getPatientResult(){
      $user = auth()->user();
      $patient = User::where('type','patient')->where('user_id',$user->id)->first();

      if(!$patient){
          $msg = trans('home.patient_not_fount');
          return $this->failMsg($msg);
      }
      $data = [];
      $data['exams'] = ExamsResource::collection($patient->exams);

      return $this->successReturn('',$data);
    }



}
