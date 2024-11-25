<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/user', function (Request $request) {
    return $request->user();
});


// Route::middleware('auth:sanctum')->post('/payment', [PaymentController::class, 'createPayment']);

Route::post('/payment', [PaymentController::class, 'storePayment']);
// Route::get('/payment', [PaymentController::class, 'getPayment']);
Route::get('/payment/{id}', [PaymentController::class, 'getPayment']);

