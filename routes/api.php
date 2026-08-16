<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\FiadoController;
use App\Http\Controllers\KitController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\StatsController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    Route::get('/productos', [ProductoController::class, 'index']);
    Route::get('/productos/codigo/{codigo}', [ProductoController::class, 'porCodigo']);
    Route::post('/productos', [ProductoController::class, 'upsert']);
    Route::post('/productos/{producto}/stock', [ProductoController::class, 'updateStock']);

    Route::get('/stats/stock', [StatsController::class, 'stock']);

    Route::get('/movimientos', [MovimientoController::class, 'index']);
    Route::post('/movimientos/entrada', [MovimientoController::class, 'entrada']);
    Route::post('/movimientos/salida', [MovimientoController::class, 'salida']);
    Route::post('/movimientos/salida-kit', [MovimientoController::class, 'salidaKit']);

    Route::get('/kits', [KitController::class, 'index']);

    Route::get('/clientes', [ClienteController::class, 'index']);
    Route::get('/clientes/{cliente}', [ClienteController::class, 'show']);
    Route::post('/clientes', [ClienteController::class, 'store']);
    Route::put('/clientes/{cliente}', [ClienteController::class, 'update']);
    Route::delete('/clientes/{cliente}', [ClienteController::class, 'destroy']);
    Route::get('/clientes/{cliente}/fiados', [ClienteController::class, 'fiados']);
    Route::get('/clientes/{cliente}/deuda-total', [ClienteController::class, 'deudaTotal']);
    Route::post('/clientes/{cliente}/pagos', [ClienteController::class, 'pago']);

    Route::post('/fiados', [FiadoController::class, 'store']);
    Route::post('/fiados/{fiado}/pagos', [FiadoController::class, 'pago']);
});
