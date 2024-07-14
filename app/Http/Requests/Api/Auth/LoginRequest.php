<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function rules(): array
    {
        return [
            //

            'email' => 'required|string|email|max:255|exists:users,email',
            'password' => 'required|string|min:8',
        ];
    }
    public function messages()
    {
        return [

            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.exists' => 'The email Not Found.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 6 characters.',
         
        ];
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response = new \Illuminate\Http\JsonResponse([
            'status' => 'error',
            'errors' => $validator->errors(),
        ], 422);

        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
