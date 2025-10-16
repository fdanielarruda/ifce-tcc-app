<?php

namespace App\Http\Controllers\Web;

use App\Http\Requests\Transactions\TransactionIndexRequest;
use App\Http\Requests\Transactions\TransactionStoreAiRequest;
use App\Http\Requests\Transactions\TransactionStoreManualRequest;
use App\Services\TransactionService;
use Inertia\Inertia;

class TransactionController
{
    public function __construct(
        protected TransactionService $service
    ) {}

    public function index(TransactionIndexRequest $request)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $data = $this->service->getTransactionsGroupedByDate($month, $year);

        return Inertia::render('Transactions/TransactionIndex', $data);
    }

    public function create()
    {
        $data = $this->service->prepareDataForCreate();

        return Inertia::render('Transactions/TransactionCreate', $data);
    }

    public function storeManual(TransactionStoreManualRequest $request)
    {
        dd($request->validated());
    }

    public function storeAi(TransactionStoreAiRequest $request)
    {
        $data = $request->validated();
        $this->service->generateByIa($data);

        return redirect()->route('transactions.create')->with('success', 'Transação adicionada com sucesso');
    }
}
