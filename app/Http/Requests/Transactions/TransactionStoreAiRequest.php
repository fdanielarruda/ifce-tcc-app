<?php

namespace App\Http\Requests\Transactions;

use Illuminate\Foundation\Http\FormRequest;

class TransactionStoreAiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'description' => ['required', 'string', 'min:10', 'max:1000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'description' => 'descrição',
        ];
    }

    public function messages(): array
    {
        return [
            'description.required' => 'A descrição da transação é obrigatória.',
            'description.min' => 'A descrição deve ter pelo menos 10 caracteres para que a IA possa processar.',
            'description.max' => 'A descrição não pode ter mais de 1000 caracteres.',
        ];
    }
}
