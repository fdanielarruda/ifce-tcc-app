<?php

namespace App\Http\Requests\Transactions;

use Illuminate\Foundation\Http\FormRequest;

class TransactionStoreManualRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'description' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric'],
            'category_id' => ['required', 'exists:categories,id'],
            'type' => ['required', 'in:receita,despesa'],
            'date' => ['required', 'date', 'date_format:Y-m-d'],
            'time' => ['required', 'date_format:H:i'],
        ];
    }

    public function attributes(): array
    {
        return [
            'description' => 'descrição',
            'amount' => 'valor',
            'category_id' => 'categoria',
            'type' => 'tipo',
            'date' => 'data',
            'time' => 'hora',
        ];
    }

    public function messages(): array
    {
        return [
            'description.required' => 'A descrição é obrigatória.',
            'description.max' => 'A descrição não pode ter mais de 255 caracteres.',
            'amount.required' => 'O valor é obrigatório.',
            'category_id.required' => 'A categoria é obrigatória.',
            'category_id.exists' => 'A categoria selecionada não existe.',
            'type.required' => 'O tipo é obrigatório.',
            'type.in' => 'O tipo deve ser receita ou despesa.',
            'date.required' => 'A data é obrigatória.',
            'date.date' => 'A data deve ser uma data válida.',
            'date.date_format' => 'A data deve estar no formato Y-m-d.',
            'time.required' => 'A hora é obrigatória.',
            'time.date_format' => 'A hora deve estar no formato H:i.',
        ];
    }
}
