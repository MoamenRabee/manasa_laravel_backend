<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
class ActivateCodeRequest extends FormRequest
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
    public function rules()
    {
        return [
            'type' => 'required|string|in:system,lesson',
            'code' => 'required|string|exists:codes,code',
            'id' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    $type = request()->input('type');
                    
                    if ($type === 'system' && !DB::table('systems')->where('id', $value)->exists()) {
                        $fail('The selected id is invalid for system.');
                    }
                    
                    if ($type === 'lesson' && !DB::table('lessons')->where('id', $value)->exists()) {
                        $fail('The selected id is invalid for lesson.');
                    }
                },
            ],
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        $error = $validator->errors()->first();
        
        throw new HttpResponseException(Response::api(400, null, $error));
    }   
}
