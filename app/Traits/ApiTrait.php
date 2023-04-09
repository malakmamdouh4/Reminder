<?php

namespace App\Traits;

use App\Models\User;

trait ApiTrait
{
    public function successMsg($msg = '', $code = 200): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'user_status' =>  auth('api')->user() ? auth('api')->user()->status : '',
            'msg' => $msg,
            'key' => 'success',
            'code' => $code,
        ]);
    }

    public function failMsg($msg = '', $code = 401): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'user_status' =>  auth('api')->user() ? auth('api')->user()->status : '',
            'msg' => $msg,
            'key' => 'fail',
            'code' => $code,
        ]);
    }

    public function successReturn($msg = '', $data = [], $code = 200): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'user_status' =>  auth('api')->user() ? auth('api')->user()->status : '',
            'msg' => $msg,
            'key' => 'success',
            'code' => $code,
            'data' => $data
        ]);
    }

    public function failReturn($msg = '', $data = [], $code = 401): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'user_status' =>  auth('api')->user() ? auth('api')->user()->status : '',
            'msg' => $msg,
            'key' => 'fail',
            'code' => $code,
            'data' => $data
        ]);
    }

    public function successReturnLogin($msg = '', $data = [], $code = 200): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'user_status' => $data['user']['status'],
            'msg' => $msg,
            'key' => 'success',
            'code' => $code,
            'data' => $data
        ]);
    }

    public function requestFailsReturn($validator, $type = 'all_in_string'): \Illuminate\Http\JsonResponse
    {
        switch ($type) {
            case 'all_in_string':
                $errors = $validator->errors()->all();
                $msg = implode(',', $validator->errors()->all());
                break;
            case 'first':
                $msg = $validator->errors()->first();
                break;
            case 'all_in_array':
                $msg = $validator->errors()->all();
                break;
            default:
                $msg = 'حدث خطأ ما';
        }

        return $this->failMsg($msg, 401);
    }

    function convert2english($string) {
        $newNumbers = range(0, 9);
        $arabic = array('٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩');
        $string =  str_replace($arabic, $newNumbers, $string);
        return $string;
    }

    function phoneValidate($number = ''){
        if (substr($number, 0, 1) === '0'){
            $number = substr($number, 1);
        }
        if (substr($number, 0, 4) === '+966'){
            $number = substr($number, 4);
        }
        if (substr($number, 0, 4) === '0966'){
            $number = substr($number, 4);
        }
        if (substr($number, 0, 3) === '+20'){
            $number = substr($number, 3);
        }
        if (substr($number, 0, 3) === '020'){
            $number = substr($number, 3);
        }
        $phone = preg_replace('/\s+/', '', $number);
        return $phone;
    }

    function is_unique($key,$value){
        $user  = User::where($key , $value)->first();
        if( $user ){
            return true;
        }
        return false;
    }

    public function paginateNum(){
        return 10;
    }

    public function paginationModel($col) {
        $data = [
          'total'         => $col->total() ?? 0,
          'count'         => $col->count() ?? 0,
          'per_page'      => $col->perPage() ?? 0,
          'next_page_url' => $col->nextPageUrl() ?? '',
          'perv_page_url' => $col->previousPageUrl() ?? '',
          'current_page'  => $col->currentPage() ?? 0,
          'total_pages'   => $col->lastPage() ?? 0,
        ];

        return $data;
    }

}
