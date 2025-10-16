<?php

namespace App\Http\Controllers\Web;

use App\Http\Requests\Transactions\TransactionIndexRequest;
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
}
