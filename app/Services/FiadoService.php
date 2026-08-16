<?php

namespace App\Services;

use App\Models\Fiado;
use App\Models\FiadoItem;
use Illuminate\Support\Facades\DB;

class FiadoService
{
    public function registrarFiado(array $data, ?int $usuarioId = null): Fiado
    {
        return DB::transaction(function () use ($data, $usuarioId) {
            $total = collect($data['items'])->sum(fn ($i) => (float) $i['subtotal']);

            $fiado = Fiado::create([
                'cliente_id' => $data['cliente_id'],
                'total' => $total,
                'notas' => $data['notas'] ?? '',
                'user_id' => $usuarioId,
            ]);

            $items = collect($data['items'])->map(fn ($i) => [
                'fiado_id' => $fiado->id,
                'producto_nombre' => $i['producto_nombre'],
                'producto_id' => $i['producto_id'] ?? null,
                'cantidad' => $i['cantidad'],
                'precio_unitario' => $i['precio_unitario'],
                'subtotal' => $i['subtotal'],
            ]);

            FiadoItem::insert($items->all());

            return $fiado->fresh('fiadoItems');
        });
    }

    private function estadoPara(float $total, float $pagado): string
    {
        if ($pagado >= $total) {
            return 'pagado';
        }

        return $pagado > 0 ? 'pagado_parcial' : 'pendiente';
    }

    /**
     * El codigo anterior hacia lectura-y-luego-escritura sin ningun lock: dos pagos
     * simultaneos sobre el mismo fiado podian pisarse (el segundo sobreescribe con
     * un "pagado" calculado a partir de un valor ya viejo). lockForUpdate() dentro
     * de la transaccion bloquea la fila hasta el commit, asi que el segundo pago
     * espera y lee el valor ya actualizado por el primero.
     */
    public function registrarPago(int $fiadoId, float $monto): Fiado
    {
        return DB::transaction(function () use ($fiadoId, $monto) {
            $fiado = Fiado::where('id', $fiadoId)->lockForUpdate()->firstOrFail();

            $nuevoPagado = min((float) $fiado->pagado + $monto, (float) $fiado->total);

            $fiado->update([
                'pagado' => $nuevoPagado,
                'estado' => $this->estadoPara((float) $fiado->total, $nuevoPagado),
            ]);

            return $fiado;
        });
    }

    /** Aplica un pago del cliente distribuyendo del fiado mas antiguo al mas nuevo. */
    public function registrarPagoCliente(int $clienteId, float $montoTotal): void
    {
        DB::transaction(function () use ($clienteId, $montoTotal) {
            $fiados = Fiado::where('cliente_id', $clienteId)
                ->where('estado', '!=', 'pagado')
                ->oldest('created_at')
                ->lockForUpdate()
                ->get();

            $restante = $montoTotal;

            foreach ($fiados as $fiado) {
                if ($restante <= 0) {
                    break;
                }

                $pendiente = (float) $fiado->total - (float) $fiado->pagado;
                $pago = min($pendiente, $restante);
                $restante -= $pago;

                $nuevoPagado = (float) $fiado->pagado + $pago;

                $fiado->update([
                    'pagado' => $nuevoPagado,
                    'estado' => $this->estadoPara((float) $fiado->total, $nuevoPagado),
                ]);
            }
        });
    }
}
