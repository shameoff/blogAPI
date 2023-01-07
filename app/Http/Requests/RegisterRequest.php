<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'fullName' => 'required|string',
            'birthDate' => 'nullable|date',
            'gender' => 'nullable|in:female,male',
            'phoneNumber' => 'nullable|string',
            'email' => 'required|email:rfc,dns|unique:users,email',
            'password' => 'required|min:8',
        ];
    }


}
