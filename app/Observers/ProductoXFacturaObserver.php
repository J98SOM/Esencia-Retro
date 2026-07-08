<?php

namespace App\Observers;

use App\Models\ProductoXFactura;
use App\Models\Inventario;
use Illuminate\Support\Facades\Log;

class ProductoXFacturaObserver
{
    /**
     * Handle the ProductoXFactura "created" event.
     */
    public function created(ProductoXFactura $item): void
    {
        if ($item->producto_id) {
            $inventarios = Inventario::where('producto_id', $item->producto_id)->get();
            foreach ($inventarios as $inv) {
                if ($inv->descuento_inventario > 0) {
                    $qty = $item->cantidad * $inv->descuento_inventario;
                    $inv->stock_inicial = max(0, $inv->stock_inicial - $qty);
                    $inv->save();
                    Log::info("Inventario descontado (Created): {$inv->nombre} -{$qty}");
                }
            }
        }
    }

    /**
     * Handle the ProductoXFactura "updated" event.
     */
    public function updated(ProductoXFactura $item): void
    {
        if ($item->producto_id && $item->isDirty('cantidad')) {
            $diff = $item->cantidad - $item->getOriginal('cantidad');
            
            if ($diff != 0) {
                $inventarios = Inventario::where('producto_id', $item->producto_id)->get();
                foreach ($inventarios as $inv) {
                    if ($inv->descuento_inventario > 0) {
                        $qty = $diff * $inv->descuento_inventario;
                        // Si diff es positivo, qty es positivo, y se resta (disminuye inventario).
                        // Si diff es negativo, qty es negativo, y restar un negativo suma (devuelve inventario).
                        $inv->stock_inicial = max(0, $inv->stock_inicial - $qty);
                        $inv->save();
                        Log::info("Inventario ajustado (Updated): {$inv->nombre} diff {$qty}");
                    }
                }
            }
        }
    }

    /**
     * Handle the ProductoXFactura "deleted" event.
     */
    public function deleted(ProductoXFactura $item): void
    {
        if ($item->producto_id) {
            $inventarios = Inventario::where('producto_id', $item->producto_id)->get();
            foreach ($inventarios as $inv) {
                if ($inv->descuento_inventario > 0) {
                    $qty = $item->cantidad * $inv->descuento_inventario;
                    $inv->stock_inicial = $inv->stock_inicial + $qty;
                    $inv->save();
                    Log::info("Inventario devuelto (Deleted): {$inv->nombre} +{$qty}");
                }
            }
        }
    }
}
