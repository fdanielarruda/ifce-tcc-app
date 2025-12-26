<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transactions\TransactionStoreRequest;
use App\Services\TransactionService;

class TransactionApiController extends Controller
{
    public function __construct(
        protected TransactionService $service
    ) {}

    public function index()
    {
        return response()->json([
            'transactions' => $this->service->list()
        ]);
    }

    public function summary(string $type)
    {
        $telegramId = request()->query('telegram_id');

        if (!$telegramId) {
            return response()->json([
                'message' => 'telegram_id é obrigatório'
            ], 400);
        }

        $summary = $this->service->getSummary($telegramId, $type);

        return response()->json([
            'summary' => $summary
        ]);
    }

    public function store(TransactionStoreRequest $request)
    {
        $data = $request->validated();
        $transaction = $this->service->createByIa($data);

        return response()->json([
            'message' => 'Transação salva com sucesso',
            'transaction' => $transaction
        ]);
    }

    public function delete(int $id)
    {
        $this->service->delete($id);

        return response()->json([
            'mensagem' => 'Transação descartada com sucesso'
        ]);
    }
}
