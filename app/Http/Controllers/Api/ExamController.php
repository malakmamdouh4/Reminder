<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\QuestionsResource;
use App\Http\Resources\ExamsResource;
use App\Models\Question;
use App\Models\Exam;
use App\Models\User;
use App\Models\Result;
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
      if (isset($request['answer_id'])) {
        
        $question = Question::find($request->question_id);
        $answer = $question->answers()?->where('status','true')->first()?->id ; 
        
        $result = 0 ; 
        if($answer == $request->answer_id){
          $result = 5 ;
        }
        
        $user_result = Exam::create([
          'result' => $result,
          'time' => date("h:i:s"),
          'date' => date('Y-m-d'),
          'user_id' => $user->id,
          'exam_id' => $request->exam_id,
          'question_id' => $request->question_id,
          'answer_id' => $request->answer_id,
        ]);
        
        $exam = Result::where('exam_id',$request->exam_id)->first();
        if($exam){
              $exam->score += $result ;
              $exam->date = date('Y-m-d');
              $exam->time = date("h:i:s");
              $exam->save();
          }else{
              Result::create([
                'exam_id' => $request->exam_id,
                'score' => $result,
                'time' => date("h:i:s"),
                'date' => date('Y-m-d'),
                'user_id' => $user->id,
              ]);
          }

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
