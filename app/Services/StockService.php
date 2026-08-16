<?php

namespace App\Services;

use App\Models\Movimiento;
use App\Models\Producto;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class StockService
{
    /**
     * Un solo UPDATE (stock_actual = stock_actual + delta) es atomico a nivel de motor
     * de base de datos, sin necesidad de un stored procedure como en Postgres.
     */
    public function actualizarStock(int $productoId, int $delta): void
    {
        $afectados = Producto::where('id', $productoId)->increment('stock_actual', $delta);

        if (! $afectados) {
            throw new \RuntimeException("Producto no encontrado: {$productoId}");
        }
    }

    public function registrarEntrada(array $data, ?int $usuarioId = null): Movimiento
    {
        return DB::transaction(function () use ($data, $usuarioId) {
            $movimiento = Movimiento::create([
                'tipo' => 'entrada',
                'producto_id' => $data['producto_id'],
                'cantidad' => $data['cantidad'],
                'precio_unitario' => $data['precio_unitario'] ?? null,
                'usuario_id' => $usuarioId,
                'notas' => $data['notas'] ?? null,
            ]);

            $this->actualizarStock($data['producto_id'], (int) $data['cantidad']);

            return $movimiento;
        });
    }

    public function registrarSalida(array $data, ?int $usuarioId = null): Movimiento
    {
        return DB::transaction(function () use ($data, $usuarioId) {
            $cantidad = -abs((int) $data['cantidad']);

            $movimiento = Movimiento::create([
                'tipo' => $data['tipo'] ?? 'salida',
                'producto_id' => $data['producto_id'],
                'cantidad' => $cantidad,
                'precio_unitario' => $data['precio_unitario'] ?? null,
                'usuario_id' => $usuarioId,
                'notas' => $data['notas'] ?? null,
            ]);

            $this->actualizarStock($data['producto_id'], $cantidad);

            return $movimiento;
        });
    }

    /**
     * El caso mas fragil del sistema anterior: N inserts + un loop de N llamadas
     * de red separadas, sin ninguna atomicidad entre ellas. Ahora es una sola
     * transaccion: si un item falla, se revierte todo (ningun producto queda con
     * el stock parcialmente descontado).
     */
    public function registrarSalidaKit(array $items, string $notas = '', ?int $usuarioId = null): Collection
    {
        return DB::transaction(function () use ($items, $notas, $usuarioId) {
            $movimientos = collect($items)->map(fn ($it) => [
                'tipo' => 'salida_kit',
                'producto_id' => $it['producto_id'],
                'cantidad' => -abs((int) $it['cantidad']),
                'precio_unitario' => $it['precio_unitario'] ?? null,
                'usuario_id' => $usuarioId,
                'notas' => $notas,
            ]);

            Movimiento::insert($movimientos->all());

            foreach ($items as $it) {
                $this->actualizarStock($it['producto_id'], -abs((int) $it['cantidad']));
            }

            return $movimientos;
        });
    }
}
