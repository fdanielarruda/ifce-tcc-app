<?php

namespace App\Http\Requests\Transactions;

use Illuminate\Foundation\Http\FormRequest;

class TransactionStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'telegram_id' => ['required', 'string', 'exists:users,telegram_id'],
            'original_message' => ['required']
        ];
    }

    public function messages(): array
    {
        return [
            'telegram_id.required' => 'O campo ID do Telegram é obrigatório.',
            'telegram_id.string' => 'O ID do Telegram deve ser uma sequência de texto.',
            'telegram_id.exists' => 'O ID do Telegram fornecido não está cadastrado em nosso sistema.',
            'original_message.required' => 'A mensagem original da transação é obrigatória.',
        ];
    }
}
