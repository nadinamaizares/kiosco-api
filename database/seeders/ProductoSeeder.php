<?php

namespace Database\Seeders;

use App\Models\Producto;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $productos = [
            ['nombre' => 'Coca-Cola 500ml', 'marca' => 'Coca-Cola', 'categoria' => 'bebidas', 'especificacion' => '500ml, botella', 'precio_costo' => 800, 'precio_venta' => 1400, 'stock_actual' => 48, 'stock_minimo' => 12],
            ['nombre' => 'Sprite 500ml', 'marca' => 'Sprite', 'categoria' => 'bebidas', 'especificacion' => '500ml, botella', 'precio_costo' => 780, 'precio_venta' => 1300, 'stock_actual' => 30, 'stock_minimo' => 12],
            ['nombre' => 'Agua Mineral 500ml', 'marca' => 'Villavicencio', 'categoria' => 'bebidas', 'especificacion' => '500ml, sin gas', 'precio_costo' => 400, 'precio_venta' => 800, 'stock_actual' => 36, 'stock_minimo' => 12],
            ['nombre' => 'Alfajor Oreo', 'marca' => 'Oreo', 'categoria' => 'snacks', 'especificacion' => 'Triple, chocolate', 'precio_costo' => 550, 'precio_venta' => 950, 'stock_actual' => 40, 'stock_minimo' => 8],
            ['nombre' => 'Galletitas Terrabusi 150g', 'marca' => 'Terrabusi', 'categoria' => 'snacks', 'especificacion' => 'Surtidas, 150g', 'precio_costo' => 1100, 'precio_venta' => 1800, 'stock_actual' => 25, 'stock_minimo' => 6],
            ['nombre' => 'Leche La Serenísima 1L', 'marca' => 'La Serenísima', 'categoria' => 'lacteos', 'especificacion' => 'Entera, 1 litro', 'precio_costo' => 1600, 'precio_venta' => 2400, 'stock_actual' => 18, 'stock_minimo' => 6],
            ['nombre' => 'Yogur Ser 200g', 'marca' => 'Ser', 'categoria' => 'lacteos', 'especificacion' => 'Natural, 200g', 'precio_costo' => 750, 'precio_venta' => 1200, 'stock_actual' => 20, 'stock_minimo' => 5],
            ['nombre' => 'Arroz Gallo Oro 1kg', 'marca' => 'Gallo', 'categoria' => 'almacen', 'especificacion' => 'Largo fino, 1kg', 'precio_costo' => 1800, 'precio_venta' => 2800, 'stock_actual' => 15, 'stock_minimo' => 4],
            ['nombre' => 'Marlboro 20un', 'marca' => 'Marlboro', 'categoria' => 'cigarrillos', 'especificacion' => 'Rojo, 20 cigarrillos', 'precio_costo' => 3200, 'precio_venta' => 4500, 'stock_actual' => 30, 'stock_minimo' => 5],
            ['nombre' => 'Shampoo Sedal 200ml', 'marca' => 'Sedal', 'categoria' => 'higiene', 'especificacion' => 'Pelo normal, 200ml', 'precio_costo' => 2400, 'precio_venta' => 3800, 'stock_actual' => 8, 'stock_minimo' => 3],
            ['nombre' => 'Jabón Dove 100g', 'marca' => 'Dove', 'categoria' => 'higiene', 'especificacion' => 'Original, 100g', 'precio_costo' => 1200, 'precio_venta' => 1900, 'stock_actual' => 12, 'stock_minimo' => 3],
            ['nombre' => 'Lavandina Ayudín 1L', 'marca' => 'Ayudín', 'categoria' => 'limpieza', 'especificacion' => 'Concentrada, 1 litro', 'precio_costo' => 1000, 'precio_venta' => 1800, 'stock_actual' => 6, 'stock_minimo' => 3],
        ];

        foreach ($productos as $p) {
            Producto::firstOrCreate(['nombre' => $p['nombre']], $p);
        }
    }
}
