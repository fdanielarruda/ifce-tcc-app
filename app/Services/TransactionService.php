<?php

namespace App\Services;

use App\Libraries\OpenAiLibrary;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    public function __construct(
        protected OpenAiLibrary $openAiLibrary
    ) {}

    public function list()
    {
        return Transaction::get();
    }

    public function getTransactionsGroupedByDate(?int $month = null, ?int $year = null)
    {
        $user = auth()->user();

        $month = $month ?? now()->month;
        $year = $year ?? now()->year;

        $allTransactions = Transaction::where('user_id', $user->id)->get();
        $totalIncome = $allTransactions->where('type', 'receita')->sum('amount');
        $totalExpenses = $allTransactions->where('type', 'despesa')->sum('amount');
        $totalBalance = $totalIncome - $totalExpenses;

        $categories = Category::orderBy('title', 'asc')->get();

        $transactions = Transaction::where('user_id', $user->id)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('created_at', 'desc')
            ->get();

        $monthlyIncome = $transactions->where('type', 'receita')->sum('amount');
        $monthlyExpenses = $transactions->where('type', 'despesa')->sum('amount');

        $grouped = [
            'today' => [],
            'yesterday' => [],
            'older' => []
        ];

        $referenceDate = Carbon::create($year, $month, 1);
        $today = now()->startOfDay();
        $yesterday = now()->subDay()->startOfDay();

        foreach ($transactions as $transaction) {
            $transactionDate = $transaction->created_at->startOfDay();

            $formattedTransaction = [
                'id' => $transaction->id,
                'description' => $transaction->description,
                'category' => $transaction->category->title,
                'category_id' => $transaction->category->id,
                'category_icon' => $transaction->category->icon,
                'time' => $transaction->created_at->format('H:i'),
                'amount' => $transaction->type === 'receita' ? abs($transaction->amount) : -abs($transaction->amount),
                'type' => $transaction->type,
                'icon' => $this->getCategoryIcon($transaction->category->title)
            ];

            if ($referenceDate->isSameMonth($today)) {
                if ($transactionDate->equalTo($today)) {
                    $grouped['today'][] = $formattedTransaction;
                } elseif ($transactionDate->equalTo($yesterday)) {
                    $grouped['yesterday'][] = $formattedTransaction;
                } else {
                    $dateKey = $transactionDate->format('Y-m-d');
                    if (!isset($grouped['older'][$dateKey])) {
                        $grouped['older'][$dateKey] = [
                            'date' => $transactionDate->format('d/m/Y'),
                            'transactions' => []
                        ];
                    }
                    $grouped['older'][$dateKey]['transactions'][] = $formattedTransaction;
                }
            } else {
                $dateKey = $transactionDate->format('Y-m-d');
                if (!isset($grouped['older'][$dateKey])) {
                    $grouped['older'][$dateKey] = [
                        'date' => $transactionDate->format('d/m/Y'),
                        'transactions' => []
                    ];
                }
                $grouped['older'][$dateKey]['transactions'][] = $formattedTransaction;
            }
        }

        return [
            'currentMonth' => Carbon::create($year, $month, 1)->locale('pt_BR')->isoFormat('MMMM YYYY'),
            'currentMonthValue' => $month,
            'currentYearValue' => $year,
            'totalBalance' => $totalBalance,
            'totalIncome' => $monthlyIncome,
            'totalExpenses' => $monthlyExpenses,
            'todayTransactions' => $grouped['today'],
            'yesterdayTransactions' => $grouped['yesterday'],
            'olderTransactions' => array_values($grouped['older']),
            'categories' => $categories
        ];
    }

    public function prepareDataForCreate()
    {
        return [
            'categories' => Category::orderBy('title', 'asc')->get()
        ];
    }

    private function getCategoryIcon(string $category): string
    {
        $icons = [
            'alimentação' => 'shopping',
            'supermercado' => 'shopping',
            'salário' => 'salary',
            'combustível' => 'fuel',
            'transporte' => 'fuel',
        ];

        return $icons[strtolower($category)] ?? 'shopping';
    }

    public function create($data)
    {
        $transaction = Transaction::create($data);

        $transaction->load(['user', 'category']);

        return $transaction;
    }

    public function createByIa($data)
    {
        DB::beginTransaction();

        try {
            $result = $this->convertDataToResult($data);

            $user = User::where('telegram_id', $data['telegram_id'])->first();

            $category_id = $this->defineCategoryId($result);

            $transaction = $this->create([
                'user_id' => $user->id,
                'category_id' => $category_id,
                'type' => $result['type'],
                'amount' => $result['amount'],
                'description' => $result['description'],
                'original_message' => $data['original_message']
            ]);

            DB::commit();

            return $transaction;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function generateByIa(array $data)
    {
        $data = [
            'original_message' => $data['description'],
            'telegram_id' => auth()->user()->telegram_id
        ];

        return $this->createByIa($data);
    }

    public function update(int $id, array $data)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->update($data);
    }

    public function delete(int $id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();
    }

    private function convertDataToResult(array $data)
    {
        $categories = Category::orderBy('title', 'asc')->pluck('title')->toArray();
        $result = $this->openAiLibrary->naturalLanguageToJsonConverter($data['original_message'], $categories);

        $required_fields = ['type', 'amount', 'description'];

        foreach ($required_fields as $field) {
            if (!isset($result[$field])) {
                throw new Exception("Não foi possível interpretar a mensagem");
            }
        }

        return $result;
    }

    private function defineCategoryId(array $result)
    {
        $find_category = trim($result['category']);

        $category = Category::where('title', $find_category);

        if ($category->exists()) {
            return $category->first()->id;
        }

        return null;
    }
}
