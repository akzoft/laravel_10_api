<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class EditPostRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        return throw new HttpResponseException(response()->json([
            'error' => true,
            'message' => 'Validation error',
            'errorList' => $validator->errors()
        ]));
    }

    public function messages()
    {
        return [
            'title.required' => 'Un titre est requis.'
        ];
    }
}
