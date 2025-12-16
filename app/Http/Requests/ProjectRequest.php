<?php

namespace App\Http\Requests;

use App\Traits\Helpers\ResponseMethodsTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProjectRequest extends FormRequest
{
    use ResponseMethodsTrait;

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'start_date' => 'nullable',
            'end_date' => 'nullable',
            'project_manager' => 'nullable',
            'description' => 'nullable',
            'fixed_price' => 'nullable',
            'edu_project_no' => 'nullable',
            'client_project_no' => 'nullable',
            'address' =>  'required|string|max:255',
            'post_code' => 'nullable',
            'city' => 'nullable',
            'weekly_statement' => 'nullable',
            'price_agreement' => 'nullable',
            'no_of_times_per_week' => 'nullable',
            'unit' => 'nullable',
            'no_of_chain' => 'nullable',
            'price' => 'nullable',
            'purchase_price' => 'nullable',
            'approval' => 'nullable',
            'notes' => 'nullable',
            'more_notes' => 'nullable',
            'active' => 'nullable',
            // 'lat' => 'required',
            // 'long' => 'required',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->sendError($validator->errors(), [], 422));
    }
}
