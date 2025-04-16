<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Response;
class ActivateSystemRequest extends FormRequest
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
            "code"=> "required|string|exists:codes,code",
            "system_id"=> "required|integer|exists:systems,id",
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        $error = $validator->errors()->first();
        
        throw new HttpResponseException(Response::api(400, null, $error));
    }   
}
