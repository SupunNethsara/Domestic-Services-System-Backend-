<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminloginRequest extends FormRequest
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
            'fname' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ];
    }
}
