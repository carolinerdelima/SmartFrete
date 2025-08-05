<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\MetricsController;

use Illuminate\Support\Carbon;

Route::post('/quote', [QuoteController::class, 'store']);
Route::get('/metrics',[MetricsController::class, 'index']);

Route::get('/', function () {
    return response()->json([
        'message' => 'SmartFrete API',
        'datetime' => Carbon::now('America/Sao_Paulo')->toIso8601String(),
    ]);
});
