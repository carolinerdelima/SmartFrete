<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuoteRequest;
use App\Services\QuoteService;
use Illuminate\Http\JsonResponse;

class QuoteController extends Controller
{
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
