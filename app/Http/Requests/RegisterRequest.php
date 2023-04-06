<?php

namespace App\Http\Requests;

use App\Traits\ApiTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

//use App\Traits\Responses;
//use Illuminate\Http\Request;

class RegisterRequest extends FormRequest
{
    use ApiTrait;

    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'first_name'    => 'required|max:255',
            'last_name'     => 'required|max:255',
            'country_key'   => 'required',
            'phone'         => ['required','min:10', 'numeric' ,'unique:users,phone'],
            'email'         => ['nullable', 'email', 'unique:users,email'],
            'password'      => 'required|confirmed|min:8',
            'gender'        => 'required',
            'date_birth'    => 'required',
            'type'          => ['required' ,Rule::in(['family', 'patient','care_giver' ])  ],
            'user_id'       => 'required_unless:type,family'
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException($this->requestFailsReturn($validator));
    }

}
