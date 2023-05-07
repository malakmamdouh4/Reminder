<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\Responses;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use App\Traits\ApiTrait;

class SignUpWithSocialRequest extends FormRequest
{
    use ApiTrait;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'social_id'   => 'required',
            'device_id'   => 'required',
            'device_type' => 'required',
            'name'        => 'nullable',
            'email'       => 'nullable',
            'phone'       => 'nullable|min:9|max:255|unique:users',
            'country_iso' => 'nullable'
        ];
            
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException($this->requestFailsReturn($validator));
    }
}
