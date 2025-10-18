<?php

namespace App\Http\Requests\Goals;

use Illuminate\Foundation\Http\FormRequest;

class GoalStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'category_id' => ['required', 'exists:categories,id'],
            'max_per_month' => ['required', 'numeric', 'min:0'],
            'message' => ['nullable', 'string', 'max:255']
        ];
    }

    public function messages()
    {
        return [
            'category_id.required' => 'A categoria é obrigatória',
            'category_id.exists' => 'Categoria inválida',
            'max_per_month.required' => 'O valor máximo é obrigatório',
            'max_per_month.numeric' => 'O valor deve ser numérico',
            'max_per_month.min' => 'O valor deve ser maior ou igual a zero',
            'message.max' => 'A mensagem não pode ter mais de 255 caracteres'
        ];
    }
}
