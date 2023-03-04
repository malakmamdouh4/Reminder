<?php

namespace App\Http\Requests;

use App\Traits\ApiTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
//use App\Traits\Responses;
//use Illuminate\Http\Request;

class LoginRequest extends FormRequest
{
    use ApiTrait;

    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'phone'         => ['required'],
            'password'      => 'required',
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException($this->requestFailsReturn($validator));
    }

}
