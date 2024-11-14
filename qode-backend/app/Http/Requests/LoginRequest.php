<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'email' => 'required|email|exists:users,email|max:255',
            'otp' => 'required|digits:6',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'An email is required for login.',
            'email.email' => 'Please enter a valid email address.',
            'email.exists' => 'No account found with this email.',
            'otp.required' => 'The OTP is required for login.',
            'otp.digits' => 'The OTP should be a 6-digit code.',
        ];
    }
}
