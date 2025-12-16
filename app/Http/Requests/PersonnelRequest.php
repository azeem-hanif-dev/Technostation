<?php

namespace App\Http\Requests;

use App\Traits\Helpers\ResponseMethodsTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PersonnelRequest extends FormRequest
{
    use ResponseMethodsTrait;

    public function rules(): array
    {
        return [
            //            'initials' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'nullable|unique:users,email',
            //            'dob' => 'required',
            //            'social_security_number' => 'required',
            //            'date_service' => 'required',
            //            'telephone' => 'required',
            //            'mobile' => 'required',
            //            'mobile2' => 'required',
            //            'mobile3' => 'required',
            //            'email' => 'required|email',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => __('messages.first_name_required'),
            'last_name.required' => __('messages.last_name_required'),
            'email.unique' => __('messages.email_unique'),
        ];
    }


    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->sendError($validator->errors(), [], 422));
    }
}
