<?php

namespace App\Http\Controllers;

use App\Services\MetricsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MetricsController extends Controller
{
    public function index(Request $request, MetricsService $metricsService): JsonResponse
    {
        $lastQuotes = $request->query('last_quotes');

        $metrics = $metricsService->getMetrics($lastQuotes);

        return response()->json($metrics);
    }
}
