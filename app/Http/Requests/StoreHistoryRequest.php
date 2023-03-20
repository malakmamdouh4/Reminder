<?php

namespace App\Http\Requests;

use App\Traits\ApiTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreHistoryRequest extends FormRequest
{
    use ApiTrait;

    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'disease'       => 'required',
            'degree'        => 'required',
            'diagnose'      => 'required',
            'symptoms'      => 'required',
            'tests'         => 'required',
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException($this->requestFailsReturn($validator));
    }

}
