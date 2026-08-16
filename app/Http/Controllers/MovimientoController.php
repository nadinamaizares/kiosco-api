<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use App\Services\StockService;
use Illuminate\Http\Request;

class MovimientoController extends Controller
{
    public function index(Request $request)
    {
        $query = Movimiento::with('producto:id,nombre,marca,categoria')
            ->orderByDesc('created_at');

        if ($request->filled('desde')) {
            $query->where('created_at', '>=', $request->input('desde'));
        }
        if ($request->filled('hasta')) {
            $query->where('created_at', '<=', $request->input('hasta'));
        }

        $query->limit((int) $request->input('limit', 300));

        return response()->json($query->get());
    }

    public function entrada(Request $request, StockService $stockService)
    {
        $data = $request->validate([
            'producto_id' => 'required|integer|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
            'precio_unitario' => 'nullable|numeric',
            'notas' => 'nullable|string',
        ]);

        $movimiento = $stockService->registrarEntrada($data, $request->user()->id);

        return response()->json($movimiento, 201);
    }

    public function salida(Request $request, StockService $stockService)
    {
        $data = $request->validate([
            'producto_id' => 'required|integer|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
            'precio_unitario' => 'nullable|numeric',
            'notas' => 'nullable|string',
            'tipo' => 'nullable|string|in:salida,salida_kit',
        ]);

        $movimiento = $stockService->registrarSalida($data, $request->user()->id);

        return response()->json($movimiento, 201);
    }

    public function salidaKit(Request $request, StockService $stockService)
    {
        $data = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.producto_id' => 'required|integer|exists:productos,id',
            'items.*.cantidad' => 'required|integer|min:1',
            'items.*.precio_unitario' => 'nullable|numeric',
            'notas' => 'nullable|string',
        ]);

        $movimientos = $stockService->registrarSalidaKit(
            $data['items'],
            $data['notas'] ?? '',
            $request->user()->id
        );

        return response()->json($movimientos, 201);
    }
}
