<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Response;
class ApiRegisterRequest extends FormRequest
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
            'name' => 'required|max:255',
            'phone' => 'required|max:11|regex:/^([0-9\s\-\+\(\)]*)$/|unique:students,phone',
            'parent_phone' => 'required|max:11|regex:/^([0-9\s\-\+\(\)]*)$/',
            'password' => 'required|min:8',
            'classroom_id' => 'required|exists:classrooms,id',
            'center_id' => 'required|exists:centers,id',
            'device_id' => 'nullable',
            'fcm_token' => 'nullable|string',
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        $error = $validator->errors()->first();
        
        throw new HttpResponseException(Response::api(400, null, $error));
    }   
}
