<?php

namespace App\Http\Controllers;

use App\Models\Producto;

class StatsController extends Controller
{
    public function stock()
    {
        $productos = Producto::select('stock_actual', 'stock_minimo')->get();

        return response()->json([
            'total' => $productos->count(),
            'bajoMin' => $productos->filter(fn ($p) => $p->stock_actual <= $p->stock_minimo)->count(),
            'sinStock' => $productos->filter(fn ($p) => (int) $p->stock_actual === 0)->count(),
        ]);
    }
}
