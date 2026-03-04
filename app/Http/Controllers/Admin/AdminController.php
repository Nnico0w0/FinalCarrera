<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminMetricsService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function __construct(private AdminMetricsService $metrics)
    {
    }

    public function index()
    {
        $report = $this->metrics->buildSummary();

        return Inertia::render('Admin/Dashboard', $report);
    }

    public function saleReport()
    {
        $report = $this->metrics->buildSummary();

        return Inertia::render('Admin/SaleReport', $report);
    }
}
