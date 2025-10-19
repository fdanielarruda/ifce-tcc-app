<?php

namespace App\Libraries;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAiLibrary
{
    protected function executar(array $messages): array
    {
        try {
            $url = env('OPENAI_API_URL');
            $token = env('OPENAI_API_KEY');
            $model = env('OPENAI_API_MODEL');

            if (!$url || !$token || !$model) {
                throw new Exception("Falta de credenciais para IA");
            }

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
        } catch (Exception $e) {
            throw new Exception($e->getMessage() ?? "Erro ao processar transação com IA");
        }
    }

    public function naturalLanguageToJsonConverter(string $text, array $categories): array
    {
        $category_list = "'" . implode("', '", $categories) . "'";
        $first_category = $categories[0];
        $date = now();

        $comando = "Extraia as informações desta transação financeira: $text\n"
            . "Retorne APENAS um JSON válido com estas chaves obrigatórias:\n"
            . "type: 'receita' ou 'despesa'\n"
            . "category: $category_list ou vazio para desconhecido\n"
            . "amount: número (apenas o valor numérico, sem símbolos)\n"
            . "description: texto curto descritivo\n"
            . "reference_date: datetime se for identificável no texto, se não, retorne vazio. hoje é $date\n"
            . "Exemplo de resposta: { 'type': 'despesa', 'category': '$first_category', 'amount': 20, 'description': 'hamburguer', 'reference_date': '2025-10-19 10:04:00' }\n"
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
