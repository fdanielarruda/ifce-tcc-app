<?php

namespace App\Services;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function prepareDataForIndex(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));

        $userId = auth()->id();

        $categoryData = $this->getCategoryData($userId, $startDate, $endDate);
        $monthlyData = $this->getMonthlyData($userId, $startDate, $endDate);
        $totals = $this->getTotals($userId, $startDate, $endDate);

        return [
            'categoryData' => $categoryData,
            'monthlyData' => $monthlyData,
            'totals' => $totals,
            'filters' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
        ];
    }

    public function exportToCSV($userId, $startDate, $endDate)
    {
        $transactions = Transaction::where('transactions.user_id', $userId)
            ->whereBetween('transactions.reference_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->select(
                'transactions.reference_date',
                'transactions.description',
                'categories.title as category',
                'transactions.type',
                'transactions.amount'
            )
            ->orderBy('transactions.reference_date', 'desc')
            ->get();

        $filename = 'relatorio_' . $startDate . '_' . $endDate . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function () use ($transactions) {
            $file = fopen('php://output', 'w');

            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, ['Data', 'Descrição', 'Categoria', 'Tipo', 'Valor'], ';');

            foreach ($transactions as $transaction) {
                $type = $transaction->type === 'receita' ? 'Receita' : 'Despesa';

                $amount = abs($transaction->amount);
                $amount = $type == 'Receita' ? $amount : -$amount;

                fputcsv($file, [
                    Carbon::parse($transaction->reference_date)->format('d/m/Y'),
                    $transaction->description,
                    $transaction->category,
                    $type,
                    number_format($amount, 2, ',', '.')
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function getCategoryData($userId, $startDate, $endDate)
    {
        return Transaction::where('transactions.user_id', $userId)
            ->whereBetween('transactions.reference_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->select(
                'categories.title as category',
                'categories.icon',
                'transactions.type',
                DB::raw('SUM(transactions.amount) as total')
            )
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->groupBy('categories.id', 'categories.title', 'categories.icon', 'transactions.type')
            ->orderBy('total', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'category' => $item->category,
                    'icon' => $item->icon,
                    'type' => $item->type,
                    'total' => (float) $item->total,
                ];
            });
    }

    private function getMonthlyData($userId, $startDate, $endDate)
    {
        $data = Transaction::where('transactions.user_id', $userId)
            ->whereBetween('transactions.reference_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->select(
                DB::raw("TO_CHAR(transactions.reference_date, 'YYYY-MM') as month"),
                'transactions.type',
                DB::raw('SUM(transactions.amount) as total')
            )
            ->groupBy('month', 'transactions.type')
            ->orderBy('month')
            ->get();

        $months = [];
        foreach ($data as $item) {
            $monthKey = $item->month;
            if (!isset($months[$monthKey])) {
                $months[$monthKey] = [
                    'month' => Carbon::createFromFormat('Y-m', $monthKey)->format('M/Y'),
                    'receita' => 0,
                    'despesa' => 0,
                ];
            }
            $months[$monthKey][$item->type] = (float) $item->total;
        }

        return array_values($months);
    }

    private function getTotals($userId, $startDate, $endDate)
    {
        $receitas = Transaction::where('user_id', $userId)
            ->whereBetween('reference_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('type', 'receita')
            ->sum('amount');

        $despesas = Transaction::where('user_id', $userId)
            ->whereBetween('reference_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('type', 'despesa')
            ->sum('amount');

        return [
            'receitas' => (float) $receitas,
            'despesas' => (float) $despesas,
            'saldo' => (float) ($receitas - $despesas),
        ];
    }
}
