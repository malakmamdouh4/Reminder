<?php

namespace App\Http\Requests;

use App\Traits\ApiTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateHistoryRequest extends FormRequest
{
    use ApiTrait;

    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'disease'       => 'nullable',
            'degree'        => 'nullable',
            'diagnose'      => 'nullable',
            'symptoms'      => 'nullable',
            'tests'         => 'nullable',
            'images'        => 'nullable',
            'history_id'    => 'required|exists:histories,id'
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException($this->requestFailsReturn($validator));
    }

}
