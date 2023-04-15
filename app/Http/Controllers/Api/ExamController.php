<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ExamsResource;
use App\Models\Question;
use App\Traits\ApiTrait;

class ExamController extends Controller
{
    use ApiTrait;

    public function getExam(){
      $user = auth()->user();
      $exams = Question::where('available','true')->get();

      $data = [];
      $data['exams'] = ExamsResource::collection($exams);

      return $this->successReturn('',$data);
    }
}
