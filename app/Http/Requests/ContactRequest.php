<?php

namespace App\Http\Requests;

use App\Traits\Helpers\ResponseMethodsTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ContactRequest extends FormRequest
{
    use ResponseMethodsTrait;

    public function rules(): array
    {
        return [
            // 'department' => 'required|numeric|exists:departments,id',
            // 'salutation' => 'required',
            // 'initials' => 'required|string',
            'departments'   => 'required|array|min:1',
            'departments.*' => 'numeric|exists:departments,id',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'function_text' => 'nullable|string',
            'active' => 'nullable|boolean',
            'dates' => 'nullable|string',
            'telephone' => 'nullable|string',
            'private_phone' => 'nullable|string',
            'mobile' => 'nullable|string',
            'mobile1' => 'nullable|string',
            // 'fax' => 'nullable|string',
            'notes' => 'nullable|string',
            'password' => 'nullable|string',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->sendError($validator->errors(), [], 422));
    }
}
