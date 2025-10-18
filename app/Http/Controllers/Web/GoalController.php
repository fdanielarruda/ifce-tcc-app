<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Goals\GoalStoreRequest;
use App\Services\GoalService;
use Inertia\Inertia;

class GoalController extends Controller
{
    protected $service;

    public function __construct(GoalService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $data = $this->service->prepareDataForIndex();

        return Inertia::render('Goals/GoalIndex', $data);
    }

    public function store(GoalStoreRequest $request)
    {
        $data = $request->validated();

        $data['user_id'] = auth()->id();

        $this->service->create($data);

        return redirect()->route('goals.index')->with('success', 'Meta adicionada com sucesso');
    }

    public function destroy($id)
    {
        $this->service->delete($id);

        return redirect()->route('goals.index')->with('success', 'Meta removida com sucesso');
    }
}
