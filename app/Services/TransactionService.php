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
        $totalIncome = $allTransactions->where('type', 'receita')->sum('value');
        $totalExpenses = $allTransactions->where('type', 'despesa')->sum('value');
        $totalBalance = $totalIncome - $totalExpenses;

        $transactions = Transaction::where('user_id', $user->id)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('created_at', 'desc')
            ->get();

        $monthlyIncome = $transactions->where('type', 'receita')->sum('value');
        $monthlyExpenses = $transactions->where('type', 'despesa')->sum('value');

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
                'title' => $transaction->description,
                'category' => $transaction->category->title,
                'time' => $transaction->created_at->format('H:i'),
                'amount' => $transaction->type === 'receita' ? $transaction->value : -$transaction->value,
                'type' => $transaction->type === 'receita' ? 'income' : 'expense',
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
            'olderTransactions' => array_values($grouped['older'])
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
        DB::beginTransaction();

        try {
            $result = $this->convertDataToResult($data);

            $user = User::where('telegram_id', $data['telegram_id'])->first();

            $category = $this->defineCategory($result);

            $transaction = Transaction::create([
                'user_id' => $user->id,
                'category_id' => $category->id,
                'type' => $result['type'],
                'value' => $result['value'],
                'description' => $result['description'],
                'original_message' => $data['original_message']
            ]);

            $transaction->load(['user', 'category']);

            DB::commit();

            return $transaction;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function delete(int $id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();
    }

    private function convertDataToResult(array $data)
    {
        $result = $this->openAiLibrary->naturalLanguageToJsonConverter($data['original_message']);
        $required_fields = ['type', 'category', 'value', 'description'];

        foreach ($required_fields as $field) {
            if (!isset($result[$field])) {
                throw new Exception("Não foi possível interpretar a mensagem");
            }
        }

        return $result;
    }

    private function defineCategory(array $result)
    {
        $new_category = strtolower(trim($result['category'] ?? 'desconhecido'));

        $category = Category::where('title', $new_category)->first();

        if ($category?->exists()) {
            return $category;
        }

        return Category::create([
            'title' => $new_category
        ]);
    }
}
