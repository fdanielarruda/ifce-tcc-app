<?php

namespace App\Libraries;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAiLibrary
{
    protected function executar(array $messages): array
    {
        $url = env('OPENAI_API_URL');
        $token = env('OPENAI_API_KEY');
        $model = env('OPENAI_API_MODEL');

        $data = [
            "model" => $model,
            "messages" => $messages,
            "temperature" => 0.3,
            "response_format" => [
                "type" => "json_object"
            ]
        ];

        $response = Http::withHeaders(['Authorization' => "Bearer $token"])->post($url, $data);

        if ($response->failed()) {
            throw new Exception('Erro ao executar a requisição na API do OpenAI: ' . $response->body());
        }

        $apiResponse = $response->json();

        Log::info("Resposta completa da API OpenAI: " . json_encode($apiResponse));

        $content = $apiResponse['choices'][0]['message']['content'];

        return json_decode($content, true);
    }

    public function naturalLanguageToJsonConverter(string $text): array
    {
        $comando = "Extraia as informações desta transação financeira: $text\n"
            . "Retorne APENAS um JSON válido com estas chaves obrigatórias:\n"
            . "type: 'receita' ou 'despesa'\n"
            . "category: 'Alimentação', 'Salário', 'Transporte', 'Lazer', 'Serviços', 'Saúde', ou 'Desconhecido'\n"
            . "value: número (apenas o valor numérico, sem símbolos)\n"
            . "description: texto curto descritivo\n"
            . "Exemplo de resposta: { 'type': 'despesa', 'category': 'Alimentação', 'value': 20, 'description', 'hamburguer' }\n"
            . "Se não conseguir extrair todas as informações obrigatórias retorne vazio";

        $messages = [
            [
                "role" => "user",
                "content" => $comando,
            ]
        ];

        return $this->executar($messages);
    }
}
