<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Services\StockService;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $query = Producto::query()->orderBy('nombre');

        if ($request->filled('search')) {
            $query->where('nombre', 'like', '%'.$request->input('search').'%');
        }
        if ($request->filled('categoria')) {
            $query->where('categoria', $request->input('categoria'));
        }

        return response()->json($query->get());
    }

    public function porCodigo(string $codigo)
    {
        $producto = Producto::where('codigo_barras', $codigo)->first();

        // response()->json(null) devuelve "{}" (Laravel/Symfony coercionan null a un
        // objeto vacio), pero el frontend espera null/falsy cuando no hay producto.
        if (! $producto) {
            return response('null', 200)->header('Content-Type', 'application/json');
        }

        return response()->json($producto);
    }

    public function upsert(Request $request)
    {
        $data = $request->validate([
            'id' => 'nullable|integer|exists:productos,id',
            'codigo_barras' => 'nullable|string|max:255',
            'nombre' => 'required|string|max:255',
            'marca' => 'nullable|string|max:255',
            'categoria' => 'nullable|string|max:255',
            'especificacion' => 'nullable|string|max:255',
            'precio_costo' => 'nullable|numeric',
            'precio_venta' => 'nullable|numeric',
            'stock_actual' => 'nullable|integer',
            'stock_minimo' => 'nullable|integer',
            'proveedor' => 'nullable|string|max:255',
        ]);

        if (! empty($data['id'])) {
            $producto = Producto::findOrFail($data['id']);
            $producto->update($data);
        } else {
            $producto = Producto::create($data);
        }

        return response()->json($producto);
    }

    public function updateStock(Request $request, Producto $producto, StockService $stockService)
    {
        $data = $request->validate(['delta' => 'required|integer']);

        $stockService->actualizarStock($producto->id, $data['delta']);

        return response()->json($producto->fresh());
    }
}
