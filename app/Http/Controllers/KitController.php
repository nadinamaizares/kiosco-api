<?php

namespace App\Http\Controllers;

use App\Models\Kit;

class KitController extends Controller
{
    public function index()
    {
        $kits = Kit::with('kitItems.producto:id,nombre,precio_venta,stock_actual')->get();

        return response()->json($kits);
    }
}
