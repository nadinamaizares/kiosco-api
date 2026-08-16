<?php

namespace App\Http\Controllers;

use App\Models\Fiado;
use App\Services\FiadoService;
use Illuminate\Http\Request;

class FiadoController extends Controller
{
    public function store(Request $request, FiadoService $fiadoService)
    {
        $data = $request->validate([
            'cliente_id' => 'required|integer|exists:clientes,id',
            'notas' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.producto_nombre' => 'required|string|max:255',
            'items.*.producto_id' => 'nullable|integer|exists:productos,id',
            'items.*.cantidad' => 'required|integer|min:1',
            'items.*.precio_unitario' => 'required|numeric',
            'items.*.subtotal' => 'required|numeric',
        ]);

        $fiado = $fiadoService->registrarFiado($data, $request->user()->id);

        return response()->json($fiado, 201);
    }

    public function pago(Request $request, Fiado $fiado, FiadoService $fiadoService)
    {
        $data = $request->validate(['monto' => 'required|numeric|min:0.01']);

        $fiado = $fiadoService->registrarPago($fiado->id, (float) $data['monto']);

        return response()->json($fiado);
    }
}
