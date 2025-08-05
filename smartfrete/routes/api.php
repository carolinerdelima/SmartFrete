<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\MetricsController;

Route::post('/quote', [QuoteController::class, 'store']);
Route::get('/metrics',[MetricsController::class, 'index']);