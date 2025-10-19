<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\ReportExportRequest;
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

    public function export(ReportExportRequest $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $user_id = auth()->id();

        return $this->service->exportToCSV($user_id, $start_date, $end_date);
    }
}
