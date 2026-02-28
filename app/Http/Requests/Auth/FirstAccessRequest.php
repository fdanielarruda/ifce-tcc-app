<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class FirstAccessRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:users,email'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'O email é obrigatório.',
            'email.email'    => 'Informe um email válido.',
            'email.exists'   => 'Email não encontrado em nosso sistema.',
        ];
    }
}
