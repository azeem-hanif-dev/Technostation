<?php

namespace App\Http\Requests;

use App\Traits\Helpers\ResponseMethodsTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ContainerSupplierRequest extends FormRequest
{
    use ResponseMethodsTrait;

    public function rules(): array
    {
        return [
            'company_name' => 'required|string',
            'code' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'address' => 'required|string',
            'city' => 'nullable|string',
            'post_code' => 'required|string',
            'notes'=> 'nullable|string',
            'mobile' => 'required|string',
             'telephone' => 'nullable|string',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->sendError($validator->errors(), [], 422));
    }
}
