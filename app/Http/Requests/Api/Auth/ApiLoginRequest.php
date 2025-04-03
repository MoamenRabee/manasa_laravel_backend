<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Response;

class ApiLoginRequest extends FormRequest
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
            'phone' => 'required|max:11|regex:/^([0-9\s\-\+\(\)]*)$/|exists:students,phone',
            'password' => 'required|min:8',
            'fcm_token' => 'nullable|string',
            'device_id' => 'required|string',
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        $error = $validator->errors()->first();
        
        throw new HttpResponseException(Response::api(400, null, $error));
    }   
}
