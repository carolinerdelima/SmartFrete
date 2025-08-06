<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

use App\Services\QuoteService;
use App\Http\Requests\QuoteRequest;

class QuoteController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/quote",
     *     summary="Solicita simulação de frete",
     *     description="Retorna cotações de frete baseadas nos dados enviados",
     *     operationId="createQuote",
     *     tags={"Cotações"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"recipient", "dispatchers", "simulation_type"},
     *             @OA\Property(
     *                 property="recipient",
     *                 type="object",
     *                 required={"zipcode"},
     *                 @OA\Property(property="zipcode", type="string", example="90200000")
     *             ),
     *             @OA\Property(
     *                 property="dispatchers",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(
     *                         property="volumes",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             required={"category", "amount", "sku", "height", "width", "length", "unitary_price", "unitary_weight"},
     *                             @OA\Property(property="category", type="string", example="1"),
     *                             @OA\Property(property="amount", type="integer", example=2),
     *                             @OA\Property(property="sku", type="string", example="AB123"),
     *                             @OA\Property(property="description", type="string", example="Caixa de livros"),
     *                             @OA\Property(property="height", type="number", format="float", example=0.2),
     *                             @OA\Property(property="width", type="number", format="float", example=0.3),
     *                             @OA\Property(property="length", type="number", format="float", example=0.4),
     *                             @OA\Property(property="unitary_price", type="number", format="float", example=45.50),
     *                             @OA\Property(property="unitary_weight", type="number", format="float", example=1.2)
     *                         )
     *                     )
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="simulation_type",
     *                 type="array",
     *                 @OA\Items(type="integer", example=0)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cotações retornadas com sucesso"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação"
     *     )
     * )
     */
    public function store(QuoteRequest $request, QuoteService $quoteService): JsonResponse
    {
        $quote = $quoteService->createQuote($request->validated());

        // Formata o retorno como o desafio exige
        $carriers = $quote->carriers->map(function ($carrier) {
            return [
                'name'     => $carrier->name,
                'service'  => $carrier->service,
                'deadline' => $carrier->deadline_days,
                'price'    => floatval($carrier->final_price),
            ];
        });

        return response()->json([
            'carrier' => $carriers,
        ]);
    }
}
