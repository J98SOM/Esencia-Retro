<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\MetodoPago;
use App\Models\ProductoXFactura;
use App\Models\RealtimeEvent;
use App\Models\AperturaCaja;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class CajaController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $roleName = strtolower(optional(auth()->user()->rol)->name ?? '');
            if (in_array($roleName, ['mesero', 'waiter'])) {
                return redirect()->route('admin.dashboard')->with('error', 'No tienes permisos para acceder a la Caja.');
            }
            return $next($request);
        });
    }

    /**
     * Show caja view with next invoice number precomputed for 'pos' tipo.
     */
    public function index(Request $request)
    {
        $max = DB::table('facturas')->where('tipo', 'pos')->select(DB::raw('MAX(CAST(numero_orden AS UNSIGNED)) as max'))->value('max');
        $next = $max ? intval($max) + 1 : 1;
        $nextStr = str_pad($next, 4, '0', STR_PAD_LEFT);

        // If the modal sent a preload payload, pass it to the view and clear it from session
        $cajaPreload = $request->session()->get('caja_preload');
        if ($cajaPreload) {
            $request->session()->forget('caja_preload');
        }

        $activeCaja = AperturaCaja::where('estado', 'abierta')->first();
        $usuarios = User::orderBy('name')->get();

        $ventasMetodos = [];
        $productosVendidos = [];
        if ($activeCaja) {
            $ventasMetodos = DB::table('metodos_pago')
                ->join('facturas', 'metodos_pago.factura_id', '=', 'facturas.id')
                ->where('facturas.id', '>', $activeCaja->last_factura_id)
                ->select('metodos_pago.metodo', DB::raw('SUM(metodos_pago.valor) as total'))
                ->groupBy('metodos_pago.metodo')
                ->get();

            $productosVendidos = DB::table('productosxfactura')
                ->join('facturas', 'productosxfactura.factura_id', '=', 'facturas.id')
                ->leftJoin('productos', 'productosxfactura.producto_id', '=', 'productos.id')
                ->where('facturas.id', '>', $activeCaja->last_factura_id)
                ->select(
                    'productosxfactura.producto_id',
                    DB::raw('COALESCE(productos.nombre, productosxfactura.descripcion) as producto_nombre'),
                    DB::raw('SUM(productosxfactura.cantidad) as cantidad_total'),
                    DB::raw('SUM(productosxfactura.cantidad * productosxfactura.precio_unitario) as total_valor')
                )
                ->groupBy('productosxfactura.producto_id', 'producto_nombre')
                ->orderByDesc('cantidad_total')
                ->get();
        }

        return view('alquiler.caja', [
            'nextInvoiceNo' => $nextStr,
            'cajaPreload' => $cajaPreload,
            'activeCaja' => $activeCaja,
            'usuarios' => $usuarios,
            'ventasMetodos' => $ventasMetodos,
            'productosVendidos' => $productosVendidos,
        ]);
    }

    /**
     * Abrir la caja con un monto inicial y un trabajador responsable.
     */
    public function apertura(Request $request)
    {
        $request->validate([
            'trabajador' => 'required|string|max:255',
            'monto_inicial' => 'required|numeric|min:0',
            'notas' => 'nullable|string',
        ]);

        // Asegurarse de que no haya una caja ya abierta
        $exists = AperturaCaja::where('estado', 'abierta')->exists();
        if ($exists) {
            return redirect()->back()->with('error', 'Ya existe una caja abierta.');
        }

        $maxFacturaId = DB::table('facturas')->max('id') ?: 0;

        AperturaCaja::create([
            'trabajador' => $request->trabajador,
            'last_factura_id' => $maxFacturaId,
            'monto_inicial' => $request->monto_inicial,
            'fecha_apertura' => now(),
            'estado' => 'abierta',
            'notas' => $request->notas,
        ]);

        return redirect()->back()->with('success', 'Caja abierta correctamente.');
    }

    public function cierre(Request $request)
    {
        $request->validate([
            'monto_final' => 'required|numeric|min:0',
            'notas' => 'nullable|string',
        ]);

        $activeCaja = AperturaCaja::where('estado', 'abierta')->first();
        if (!$activeCaja) {
            return redirect()->back()->with('error', 'No hay ninguna caja abierta para cerrar.');
        }

        // Calcular ventas por método de pago para congelar en base de datos
        $ventasMetodos = DB::table('metodos_pago')
            ->join('facturas', 'metodos_pago.factura_id', '=', 'facturas.id')
            ->where('facturas.id', '>', $activeCaja->last_factura_id)
            ->select('metodos_pago.metodo', DB::raw('SUM(metodos_pago.valor) as total'))
            ->groupBy('metodos_pago.metodo')
            ->get();

        $efectivo = 0;
        $tarjeta = 0;
        $qr = 0;

        foreach($ventasMetodos as $v) {
            $m = strtolower($v->metodo);
            if (str_contains($m, 'tarjeta') || str_contains($m, 'pos') || str_contains($m, 'visa') || str_contains($m, 'mastercard')) {
                $tarjeta += $v->total;
            } elseif (str_contains($m, 'efectivo')) {
                $efectivo += $v->total;
            } else {
                $qr += $v->total;
            }
        }

        $activeCaja->update([
            'monto_final' => $request->monto_final,
            'ventas_efectivo' => $efectivo,
            'ventas_tarjeta' => $tarjeta,
            'ventas_qr' => $qr,
            'fecha_cierre' => now(),
            'estado' => 'cerrada',
            'notas' => $activeCaja->notas . ($request->notas ? "\nNotas de Cierre: " . $request->notas : ""),
        ]);

        return redirect()->back()->with('success', 'Caja cerrada correctamente.');
    }

    /**
     * Store a simple caja sale from the caja view.
     */
    public function store(Request $request)
    {
        $invoiceInput = $request->input('invoice');
        $invoice = [];

        if (is_array($invoiceInput)) {
            $invoice = $invoiceInput;
        } elseif (is_string($invoiceInput) && $invoiceInput !== '') {
            $decoded = json_decode($invoiceInput, true);
            if (is_array($decoded)) {
                $invoice = $decoded;
            }
        } elseif ($request->isJson()) {
            $payload = $request->json()->all();
            if (is_array($payload) && ! empty($payload)) {
                $invoice = $payload;
            }
        }

        if (empty($invoice)) {
            return response()->json(['message' => 'Invoice payload missing'], 422);
        }

        DB::beginTransaction();
        try {
            $tipo = $invoice['tipo'] ?? 'pos';
            $max = DB::table('facturas')->where('tipo', $tipo)->select(DB::raw('MAX(CAST(numero_orden AS UNSIGNED)) as max'))->lockForUpdate()->value('max');
            $next = $max ? intval($max) + 1 : 1;
            $numeroOrden = str_pad($next, 4, '0', STR_PAD_LEFT);

            // If a factura_id or mesa_id was provided and there's an existing pending factura,
            // finalize (update) that same factura instead of creating a new one.
            $mesaId = $invoice['mesa_id'] ?? null;
            $facturaId = $invoice['factura_id'] ?? null;
            $factura = null;

            if ($facturaId) {
                $factura = Factura::whereKey($facturaId)->lockForUpdate()->first();
            }

            if (! $factura && $mesaId) {
                $factura = Factura::where('mesa_id', $mesaId)
                    ->whereNotIn(DB::raw('LOWER(estatus)'), ['pagado', 'pagada'])
                    ->orderBy('fecha', 'desc')
                    ->lockForUpdate()
                    ->first();
            }

            $targetMesaId = $mesaId ?: ($factura ? $factura->mesa_id : null);
            if ($targetMesaId) {
                $mesaObj = \App\Models\Mesa::find($targetMesaId);
                if ($mesaObj && $mesaObj->es_admin) {
                    $invoice['total'] = 0;
                    $invoice['cambio'] = 0;
                    if (!empty($invoice['items']) && is_array($invoice['items'])) {
                        foreach ($invoice['items'] as $idx => $it) {
                            if (isset($invoice['items'][$idx]['precio'])) {
                                $invoice['items'][$idx]['precio'] = 0;
                            }
                            if (isset($invoice['items'][$idx]['precio_unitario'])) {
                                $invoice['items'][$idx]['precio_unitario'] = 0;
                            }
                        }
                    }
                }
            }

            if ($factura) {
                $factura->tipo = $tipo;
                $factura->numero_orden = $factura->numero_orden ?: $numeroOrden;
                $factura->fecha = $invoice['fecha'] ?? $factura->fecha;
                $factura->persona = $invoice['cliente']['nombre'] ?? $factura->persona;
                $factura->nit = $invoice['cliente']['nit'] ?? $factura->nit;
                $factura->direccion = $invoice['cliente']['direccion'] ?? $factura->direccion;
                $factura->telefono = $invoice['cliente']['telefono'] ?? $factura->telefono;
                $factura->ciudad = $invoice['cliente']['ciudad'] ?? $factura->ciudad;
                $factura->orden_compra = $invoice['orden_compra'] ?? $factura->orden_compra;
                $factura->observaciones = $invoice['observaciones'] ?? $factura->observaciones;
                $factura->mesa_id = $mesaId ?? $factura->mesa_id;
                $factura->estatus = 'pagada';
                $factura->monto_total = $invoice['total'] ?? $factura->monto_total;
                $factura->cambio = $invoice['cambio'] ?? ($factura->cambio ?? 0);
                $factura->save();
            }

            if (! $factura) {
                // Create a new factura; if mesa_id was provided associate it
                $factura = Factura::create([
                    'tipo' => $tipo,
                    'numero_orden' => $numeroOrden,
                    'fecha' => $invoice['fecha'] ?? now()->toDateString(),
                    'persona' => $invoice['cliente']['nombre'] ?? 'Caja',
                    'nit' => $invoice['cliente']['nit'] ?? null,
                    'direccion' => $invoice['cliente']['direccion'] ?? null,
                    'telefono' => $invoice['cliente']['telefono'] ?? null,
                    'ciudad' => $invoice['cliente']['ciudad'] ?? null,
                    'orden_compra' => $invoice['orden_compra'] ?? null,
                    'observaciones' => $invoice['observaciones'] ?? null,
                    'mesa_id' => $mesaId,
                    'estatus' => 'pagada',
                    'monto_total' => $invoice['total'] ?? 0,
                    'cambio' => $invoice['cambio'] ?? 0,
                ]);
            }

            ProductoXFactura::where('factura_id', $factura->id)->get()->each->delete();
            if (! empty($invoice['items']) && is_array($invoice['items'])) {
                $hasDescripcion = Schema::hasColumn('productosxfactura', 'descripcion');
                $insertData = [];
                $now = now();
                foreach ($invoice['items'] as $it) {
                    $data = [
                        'producto_id' => $it['producto_id'] ?? null,
                        'factura_id' => $factura->id,
                        'cantidad' => $it['cant'] ?? ($it['cantidad'] ?? 0),
                        'precio_unitario' => $it['precio'] ?? ($it['precio_unitario'] ?? 0),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                    if ($hasDescripcion) {
                        $data['descripcion'] = $it['desc'] ?? ($it['descripcion'] ?? null);
                    }
                    $insertData[] = $data;
                }
                if (!empty($insertData)) {
                    foreach ($insertData as $data) {
                        ProductoXFactura::create($data);
                    }
                }
            }

            MetodoPago::where('factura_id', $factura->id)->delete();
            if (! empty($invoice['metodos']) && is_array($invoice['metodos'])) {
                $metodosData = [];
                $now = now();
                foreach ($invoice['metodos'] as $mp) {
                    $metodosData[] = ['factura_id' => $factura->id, 'metodo' => $mp['metodo'] ?? null, 'valor' => $mp['valor'] ?? 0, 'created_at' => $now, 'updated_at' => $now];
                }
                if (!empty($metodosData)) {
                    MetodoPago::insert($metodosData);
                }
            } elseif (! empty($invoice['medio_pago'])) {
                MetodoPago::create(['factura_id' => $factura->id, 'metodo' => $invoice['medio_pago'], 'valor' => $invoice['total'] ?? 0]);
            }

            DB::commit();

            RealtimeEvent::record('factura.pagada', [
                'entity_type' => 'factura',
                'entity_id' => $factura->id,
                'mesa_id' => $factura->mesa_id,
                'tipo' => $factura->tipo,
                'estatus' => $factura->estatus,
                'numero_orden' => $factura->numero_orden,
                'source' => 'caja',
            ]);

            return response()->json(['message' => 'Venta guardada', 'factura_id' => $factura->id, 'numero_orden' => $factura->numero_orden, 'redirect' => route('alquiler.list')]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error creando venta caja: '.$e->getMessage(), ['exception' => $e]);

            return response()->json(['message' => 'Error creando venta', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update an existing caja factura (tipo 'pos').
     */
    public function update(Request $request, $id = null)
    {
        $invoiceInput = $request->input('invoice');
        $invoice = [];

        if (is_array($invoiceInput)) {
            $invoice = $invoiceInput;
        } elseif (is_string($invoiceInput) && $invoiceInput !== '') {
            $decoded = json_decode($invoiceInput, true);
            if (is_array($decoded)) {
                $invoice = $decoded;
            }
        } elseif ($request->isJson()) {
            $payload = $request->json()->all();
            if (is_array($payload) && ! empty($payload)) {
                if (array_key_exists('invoice', $payload) && is_array($payload['invoice'])) {
                    $invoice = $payload['invoice'];
                } else {
                    $invoice = $payload;
                }
            }
        }

        if ((empty($invoice) || empty($invoice['factura_id'])) && $id) {
            $invoice['factura_id'] = $id;
        }

        if (empty($invoice) || empty($invoice['factura_id'])) {
            return response()->json(['message' => 'Invoice or factura_id missing'], 422);
        }

        $factura = Factura::find($invoice['factura_id']);
        if (! $factura) {
            return response()->json(['message' => 'Factura no encontrada'], 404);
        }

        if ($factura->mesa_id) {
            $mesaObj = \App\Models\Mesa::find($factura->mesa_id);
            if ($mesaObj && $mesaObj->es_admin) {
                $invoice['total'] = 0;
                $invoice['cambio'] = 0;
                if (!empty($invoice['items']) && is_array($invoice['items'])) {
                    foreach ($invoice['items'] as $idx => $it) {
                        if (isset($invoice['items'][$idx]['precio'])) {
                            $invoice['items'][$idx]['precio'] = 0;
                        }
                        if (isset($invoice['items'][$idx]['precio_unitario'])) {
                            $invoice['items'][$idx]['precio_unitario'] = 0;
                        }
                    }
                }
            }
        }

        DB::beginTransaction();
        try {
            $factura->update([
                'fecha' => $invoice['fecha'] ?? $factura->fecha,
                'persona' => $invoice['cliente']['nombre'] ?? $factura->persona,
                'nit' => $invoice['cliente']['nit'] ?? $factura->nit,
                'direccion' => $invoice['cliente']['direccion'] ?? $factura->direccion,
                'telefono' => $invoice['cliente']['telefono'] ?? $factura->telefono,
                'ciudad' => $invoice['cliente']['ciudad'] ?? $factura->ciudad,
                'orden_compra' => $invoice['orden_compra'] ?? $factura->orden_compra,
                'observaciones' => $invoice['observaciones'] ?? $factura->observaciones,
                'monto_total' => $invoice['total'] ?? $factura->monto_total,
            ]);

            // Recreate items
            ProductoXFactura::where('factura_id', $factura->id)->get()->each->delete();
            if (! empty($invoice['items']) && is_array($invoice['items'])) {
                $hasDescripcion = Schema::hasColumn('productosxfactura', 'descripcion');
                $insertData = [];
                $now = now();
                foreach ($invoice['items'] as $it) {
                    $data = [
                        'producto_id' => $it['producto_id'] ?? null,
                        'factura_id' => $factura->id,
                        'cantidad' => $it['cant'] ?? ($it['cantidad'] ?? 0),
                        'precio_unitario' => $it['precio'] ?? ($it['precio_unitario'] ?? 0),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                    if ($hasDescripcion) {
                        $data['descripcion'] = $it['desc'] ?? ($it['descripcion'] ?? null);
                    }
                    $insertData[] = $data;
                }
                if (!empty($insertData)) {
                    foreach ($insertData as $data) {
                        ProductoXFactura::create($data);
                    }
                }
            }

            // Recreate metodos
            MetodoPago::where('factura_id', $factura->id)->delete();
            if (! empty($invoice['metodos']) && is_array($invoice['metodos'])) {
                $metodosData = [];
                $now = now();
                foreach ($invoice['metodos'] as $mp) {
                    $metodosData[] = ['factura_id' => $factura->id, 'metodo' => $mp['metodo'] ?? null, 'valor' => $mp['valor'] ?? 0, 'created_at' => $now, 'updated_at' => $now];
                }
                if (!empty($metodosData)) {
                    MetodoPago::insert($metodosData);
                }
            } elseif (! empty($invoice['medio_pago'])) {
                MetodoPago::create(['factura_id' => $factura->id, 'metodo' => $invoice['medio_pago'], 'valor' => $invoice['total'] ?? 0]);
            }

            DB::commit();

            RealtimeEvent::record('factura.pagada', [
                'entity_type' => 'factura',
                'entity_id' => $factura->id,
                'mesa_id' => $factura->mesa_id,
                'tipo' => $factura->tipo,
                'estatus' => $factura->estatus,
                'numero_orden' => $factura->numero_orden,
                'source' => 'caja',
            ]);

            return response()->json(['message' => 'Venta actualizada', 'factura_id' => $factura->id]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error actualizando venta caja: '.$e->getMessage(), ['exception' => $e]);

            return response()->json(['message' => 'Error actualizando venta', 'error' => $e->getMessage()], 500);
        }
    }
}
