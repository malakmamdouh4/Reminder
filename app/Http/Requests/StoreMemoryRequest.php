<?php

namespace App\Http\Requests;

use App\Traits\ApiTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreMemoryRequest extends FormRequest
{
    use ApiTrait;

    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'title'               => 'required',
            'date'                => 'required_if:type,==,occasional',
            'type'                => 'required',
            'media'               => 'required',
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException($this->requestFailsReturn($validator));
    }

}
