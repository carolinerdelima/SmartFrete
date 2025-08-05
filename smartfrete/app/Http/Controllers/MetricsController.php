<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

use App\Services\MetricsService;
use App\Http\Requests\MetricsRequest;

class MetricsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/metrics",
     *     summary="Retorna métricas agregadas de cotações",
     *     description="Retorna as métricas por transportadora, o frete mais barato e o mais caro.",
     *     operationId="getMetrics",
     *     tags={"Métricas"},
     *     @OA\Parameter(
     *         name="last_quotes",
     *         in="query",
     *         description="Número de últimas cotações a considerar (ex: 10). Se omitido, considera todas.",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             example=10
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Métricas retornadas com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="by_carrier", type="array", @OA\Items(
     *                 @OA\Property(property="carrier", type="string", example="JT"),
     *                 @OA\Property(property="quotes_count", type="integer", example=3),
     *                 @OA\Property(property="total_freight", type="string", example="2007.56"),
     *                 @OA\Property(property="average_freight", type="string", example="669.19")
     *             )),
     *             @OA\Property(property="cheapest_freight", type="object",
     *                 @OA\Property(property="carrier", type="string", example="AZUL CARGO"),
     *                 @OA\Property(property="service", type="string", example="Convencional"),
     *                 @OA\Property(property="freight_price", type="string", example="13.87"),
     *                 @OA\Property(property="delivery_days", type="integer", example=2)
     *             ),
     *             @OA\Property(property="most_expensive_freight", type="object",
     *                 @OA\Property(property="carrier", type="string", example="RAPIDÃO FR (TESTE)"),
     *                 @OA\Property(property="service", type="string", example="teste2"),
     *                 @OA\Property(property="freight_price", type="string", example="4596.55"),
     *                 @OA\Property(property="delivery_days", type="integer", example=5)
     *             )
     *         )
     *     )
     * )
     */
    public function index(MetricsRequest $request, MetricsService $metricsService): JsonResponse
    {
        $lastQuotes = $request->validated('last_quotes');

        $metrics = $metricsService->getMetrics($lastQuotes);

        return response()->json($metrics);
    }
}
