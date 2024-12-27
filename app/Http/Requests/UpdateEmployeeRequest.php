<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['string', 'max:55'],
            'company_name' => ['string', 'max:95'],
            'normal_hourly_rate' => ['numeric', 'regex:/^\d{1,6}(\.\d{1,2})?$/'],
            'overtime_hourly_rate' => ['numeric', 'regex:/^\d{1,6}(\.\d{1,2})?$/'],
            'holiday_hourly_rate' => ['numeric', 'regex:/^\d{1,6}(\.\d{1,2})?$/'],
            'irpf' => ['numeric', 'regex:/^\d{1,2}(\.\d{1,2})?$/'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
