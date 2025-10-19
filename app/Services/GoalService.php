<?php

namespace App\Services;

use App\Models\UserGoal;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class GoalService
{
    public function list()
    {
        return UserGoal::with(['category', 'user'])
            ->where('user_id', auth()->id())
            ->get();
    }

    public function prepareDataForIndex()
    {
        $goals = $this->list();

        $categories = Category::orderBy('title')->get();

        $currentMonth = now()->month;
        $currentYear = now()->year;

        $expenses = Transaction::select('category_id', DB::raw('SUM(ABS(amount)) as total'))
            ->where('user_id', auth()->id())
            ->where('type', 'despesa')
            ->whereMonth('reference_date', $currentMonth)
            ->whereYear('reference_date', $currentYear)
            ->groupBy('category_id')
            ->pluck('total', 'category_id');

        $goalsWithExpenses = $goals->map(function ($goal) use ($expenses) {
            $currentExpense = $expenses->get($goal->category_id, 0);

            return [
                'id' => $goal->id,
                'category_id' => $goal->category_id,
                'category' => $goal->category,
                'max_per_month' => $goal->max_per_month,
                'message' => $goal->message,
                'current_expense' => $currentExpense,
                'percentage' => $goal->max_per_month > 0
                    ? min(($currentExpense / $goal->max_per_month) * 100, 100)
                    : 0,
                'is_over_limit' => $currentExpense > $goal->max_per_month,
            ];
        });

        return [
            'goals' => $goalsWithExpenses,
            'categories' => $categories,
            'currentMonth' => now()->isoFormat('MMMM YYYY'),
        ];
    }

    public function create($data)
    {
        $goal = UserGoal::create($data);

        $goal->load(['user', 'category']);

        return $goal;
    }

    public function delete($id)
    {
        $goal = UserGoal::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return $goal->delete();
    }
}
