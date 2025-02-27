<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TravelOrderController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:api')->group(function () {
    Route::post('/travel-orders', [TravelOrderController::class, 'store']);
    Route::get('/travel-orders', [TravelOrderController::class, 'index']);
    Route::get('/travel-orders/{id}', [TravelOrderController::class, 'show']);
    Route::patch('/travel-orders/{id}/status', [TravelOrderController::class, 'updateStatus']);
});