<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuoteRequest;
use App\Services\QuoteService;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class QuoteController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/quote",
     *     summary="Simular cotação de frete",
     *     tags={"Frete"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"recipient", "simulation_type", "volumes"},
     *             @OA\Property(property="recipient", type="object",
     *                 @OA\Property(property="address", type="object",
     *                     @OA\Property(property="zipcode", type="string", example="29161376")
     *                 )
     *             ),
     *             @OA\Property(property="simulation_type", type="array", @OA\Items(type="integer"), example={0}),
     *             @OA\Property(property="volumes", type="array",
     *                 @OA\Items(type="object",
     *                     @OA\Property(property="category", type="string", example="8"),
     *                     @OA\Property(property="amount", type="integer", example=2),
     *                     @OA\Property(property="unitary_weight", type="number", format="float", example=1.5),
     *                     @OA\Property(property="unitary_price", type="number", format="float", example=250.0),
     *                     @OA\Property(property="sku", type="string", example="PROD001"),
     *                     @OA\Property(property="height", type="number", format="float", example=10.0),
     *                     @OA\Property(property="width", type="number", format="float", example=15.0),
     *                     @OA\Property(property="length", type="number", format="float", example=20.0)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="carrier", type="array", @OA\Items(
     *                 @OA\Property(property="name", type="string", example="Transportadora X"),
     *                 @OA\Property(property="service", type="string", example="Expresso"),
     *                 @OA\Property(property="deadline", type="integer", example=3),
     *                 @OA\Property(property="price", type="number", format="float", example=23.5)
     *             ))
     *         )
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
