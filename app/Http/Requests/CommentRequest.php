<?php

namespace App\Http\Requests;

use App\Traits\Helpers\ResponseMethodsTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CommentRequest extends FormRequest
{
    use ResponseMethodsTrait;

    public function rules(): array
    {
        return [
            'comment' => 'required|string'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->sendError($validator->errors(), [], 422));
    }
}
