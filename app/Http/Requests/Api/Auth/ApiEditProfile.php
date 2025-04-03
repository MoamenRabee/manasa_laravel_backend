<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Response;
class ApiEditProfile extends FormRequest
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
            'name' => 'required|string|max:255',
            'password'=> 'nullable|string|min:8|confirmed',
            'password_confirmation'=> 'nullable|string|min:8',
            'phone' => 'nullable|string|max:255|unique:students,phone',
            'parent_phone'=> 'nullable|string|max:255',
            'classroom_id'=> 'nullable|exists:classrooms,id',
            'center_id'=> 'nullable|exists:centers,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        $error = $validator->errors()->first();
        
        throw new HttpResponseException(Response::api(400, null, $error));
    }



}
