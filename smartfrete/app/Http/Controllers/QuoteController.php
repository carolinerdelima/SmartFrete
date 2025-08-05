<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuoteRequest;
use App\Services\QuoteService;
use Illuminate\Http\JsonResponse;

class QuoteController extends Controller
{
    public function __construct(protected QuoteService $quoteService)
    {
    }

    /**
     * Recebe uma solicitação de cotação e retorna as ofertas disponíveis.
     */
    public function store(QuoteRequest $request): JsonResponse
    {
        try {
            $quote = $this->quoteService->createQuote($request->validated());

            $carriers = $quote->carriers->map(function ($carrier) {
                return [
                    'name'     => $carrier->name,
                    'service'  => $carrier->service,
                    'deadline' => $carrier->deadline_days,
                    'price'    => (float) $carrier->final_price,
                ];
            });

            return response()->json([
                'carrier' => $carriers,
            ], 201);
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'message' => 'Erro ao processar a cotação.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
