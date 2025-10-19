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

        // Dados para o gráfico de rosca (por categoria)
        $categoryData = $this->getCategoryData($userId, $startDate, $endDate);

        // Dados para o gráfico de barras (entrada e saída por mês)
        $monthlyData = $this->getMonthlyData($userId, $startDate, $endDate);

        // Totais gerais
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

        // Organiza os dados por mês
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
