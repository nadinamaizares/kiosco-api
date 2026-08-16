<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Services\FiadoService;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Antes el frontend pedia getDeudaTotal(cliente_id) una vez por cliente en la
     * lista (N+1: una request HTTP por fila). Aca la deuda ya viene calculada por
     * fila en una sola consulta, via dos withSum agregados.
     */
    public function index(Request $request)
    {
        $query = Cliente::query()
            ->withSum(['fiados as total_fiado' => fn ($q) => $q->where('estado', '!=', 'pagado')], 'total')
            ->withSum(['fiados as pagado_fiado' => fn ($q) => $q->where('estado', '!=', 'pagado')], 'pagado')
            ->orderBy('apellido');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                    ->orWhere('apellido', 'like', "%{$search}%")
                    ->orWhere('dni', 'like', "%{$search}%");
            });
        }

        $clientes = $query->get()->map(function (Cliente $c) {
            $c->deuda_total = (float) ($c->total_fiado ?? 0) - (float) ($c->pagado_fiado ?? 0);

            return $c;
        });

        return response()->json($clientes);
    }

    public function show(Cliente $cliente)
    {
        return response()->json($cliente);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'dni' => 'nullable|string|max:255',
            'celular' => 'required|string|max:255',
            'mail' => 'nullable|email|max:255',
        ]);

        $data['user_id'] = $request->user()->id;

        $cliente = Cliente::create($data);

        return response()->json($cliente, 201);
    }

    public function update(Request $request, Cliente $cliente)
    {
        $data = $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'apellido' => 'sometimes|required|string|max:255',
            'dni' => 'nullable|string|max:255',
            'celular' => 'sometimes|required|string|max:255',
            'mail' => 'nullable|email|max:255',
        ]);

        $cliente->update($data);

        return response()->json($cliente);
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();

        return response()->json(null, 204);
    }

    public function fiados(Cliente $cliente)
    {
        $fiados = $cliente->fiados()->with('fiadoItems')->orderByDesc('created_at')->get();

        return response()->json($fiados);
    }

    public function deudaTotal(Cliente $cliente)
    {
        $deuda = $cliente->fiados()
            ->where('estado', '!=', 'pagado')
            ->get()
            ->reduce(fn ($acc, $f) => $acc + ((float) $f->total - (float) $f->pagado), 0.0);

        return response()->json(['deuda_total' => $deuda]);
    }

    public function pago(Request $request, Cliente $cliente, FiadoService $fiadoService)
    {
        $data = $request->validate(['monto' => 'required|numeric|min:0.01']);

        $fiadoService->registrarPagoCliente($cliente->id, (float) $data['monto']);

        return response()->json(['message' => 'Pago registrado']);
    }
}
