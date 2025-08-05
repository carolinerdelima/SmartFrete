<?php

namespace App\Http\Controllers;

use App\Http\Requests\MetricsRequest;
use App\Services\MetricsService;
use Illuminate\Http\JsonResponse;

class MetricsController extends Controller
{
    public function index(MetricsRequest $request, MetricsService $metricsService): JsonResponse
    {
        $lastQuotes = $request->validated('last_quotes');

        $metrics = $metricsService->getMetrics($lastQuotes);

        return response()->json($metrics);
    }
}
