<?php

use App\Http\Controllers\Api\{
    UserController,
    ContaController,
    RegisterController
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group( function () {

    Route::get('/usuario', [UserController::class, 'index']);
    Route::put('/usuario', [UserController::class, 'update']);

    Route::post('/conta', [ContaController::class, 'create']);
    Route::get('/conta/{numero}', [ContaController::class, 'index']);
    Route::post('/conta/{numero}/deposito', [ContaController::class, 'depositar']);
    Route::post('/conta/{numero}/pagamento', [ContaController::class, 'pagar']);
    Route::post('/conta/{numero}/transferir', [ContaController::class, 'transferir']);
});

Route::post('/usuario/login', [RegisterController::class, 'login']);
Route::post('/usuario', [RegisterController::class, 'register']);


Route::get('/', function () {
    return response()->json(['message' => 'success']);
});
