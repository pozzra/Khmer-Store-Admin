<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

Route::post('/orders', [\App\Http\Controllers\OrderController::class, 'apiStore']);
