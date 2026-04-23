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

        $comando = "Extraia as informações desta transação ou saldo financeiro: $text\n"
            . "Retorne APENAS um JSON válido com estas chaves obrigatórias:\n"
            . "type: 'receita', 'despesa' ou 'saldo' (use 'saldo' quando o usuário informar quanto tem na conta, ex: 'tenho 1000 reais', 'mil reais na conta')\n"
            . "category: $category_list, procure outros ou vazio para desconhecido\n"
            . "amount: número (apenas o valor numérico, sem símbolos. Converta valores por extenso, ex: 'mil' = 1000)\n"
            . "description: texto curto descritivo\n"
            . "reference_date: datetime se for identificável no texto, se não, retorne vazio. hoje é $date\n"
            . "Exemplo de resposta para saldo: { 'type': 'saldo', 'category': '', 'amount': 1000, 'description': 'saldo em conta', 'reference_date': '' }\n"
            . "Exemplo de resposta para despesa: { 'type': 'despesa', 'category': '$first_category', 'amount': 20, 'description': 'hamburguer', 'reference_date': '2025-10-19 10:04:00' }\n"
            . "Se não conseguir extrair um valor monetário e uma descrição mínima da mensagem, retorne: { \"error\": \"Não foi possível interpretar a mensagem\" }";

        $messages = [
            [
                "role" => "user",
                "content" => $comando,
            ]
        ];

        return $this->executar($messages);
    }
}
