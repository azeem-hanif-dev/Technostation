<?php

namespace App\Http\Requests;

use App\Traits\Helpers\ResponseMethodsTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderWasteContainerRequest extends FormRequest
{
    use ResponseMethodsTrait;

    public function rules(): array
    {
        return [
            'supplier' => 'required',
            'project' => 'required',
            'order_date_time' => 'required',
            'execution_date' => 'required',
            //            'approved_by' => 'required',
            //            'order_by' => 'required',
            'desired_time' => 'required',
            //            'notes' => 'required',
            //            'comments' => 'required',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->sendError($validator->errors(), [], 422));
    }
}
