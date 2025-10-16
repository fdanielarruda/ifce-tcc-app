<?php

namespace App\Http\Controllers\API;

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

    public function store(TransactionStoreRequest $request)
    {
        $data = $request->validated();
        $transaction = $this->service->create($data);

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
