<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuoteController;

Route::get('/ping', fn() => response()->json(['pong' => true]));
Route::get('/quotes', [QuoteController::class, 'quotes']);
Route::get('/history', [QuoteController::class, 'history']);
