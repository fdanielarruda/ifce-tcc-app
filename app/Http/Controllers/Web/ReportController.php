<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function __construct(
        private ReportService $service
    ) {}

    public function index(Request $request)
    {
        $data = $this->service->prepareDataForIndex($request);

        return Inertia::render('Reports/ReportIndex', $data);
    }
}
