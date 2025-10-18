<?php

namespace App\Services;

use App\Models\UserGoal;
use App\Models\Category;

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

        return [
            'goals' => $goals,
            'categories' => $categories
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
