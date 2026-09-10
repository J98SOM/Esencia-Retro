<?php

namespace App\Observers;

use App\Models\ProductoXFactura;
use App\Models\Producto;
use App\Models\Inventario;
use Illuminate\Support\Facades\Log;

class ProductoXFacturaObserver
{
    /**
     * Handle the ProductoXFactura "created" event.
     */
    public function created(ProductoXFactura $item): void
    {
        $producto = $item->producto_id ? Producto::find($item->producto_id) : null;
        // Si no es un petaco, se descuenta el stock regular del producto
        if (!$producto || !$producto->esPetaco()) {
            $this->applyInventoryChange($item, (float)$item->cantidad, false, 'Created');
        }
    }

    /**
     * Handle the ProductoXFactura "updated" event.
     */
    public function updated(ProductoXFactura $item): void
    {
        if ($item->isDirty('cantidad')) {
            $diff = (float)$item->cantidad - (float)$item->getOriginal('cantidad');
            
            $producto = $item->producto_id ? Producto::find($item->producto_id) : null;
            $isPetaco = $producto && $producto->esPetaco();

            if ($isPetaco) {
                // Ajustar cada componente del petaco proporcionalmente al cambio de cantidad
                $petacoItems = \App\Models\PetacoProducto::where('productosxfactura_id', $item->id)->get();
                foreach ($petacoItems as $petacoItem) {
                    $units = (float)$petacoItem->cantidad * abs($diff);
                    if ($diff > 0) {
                        $this->updateSingleProductStock($petacoItem->producto_id, $units, false, 'Petaco Cantidad Aumentada');
                    } elseif ($diff < 0) {
                        $this->updateSingleProductStock($petacoItem->producto_id, $units, true, 'Petaco Cantidad Disminuida');
                    }
                }
            } else {
                if ($diff > 0) {
                    $this->applyInventoryChange($item, $diff, false, 'Updated (Increased)');
                } elseif ($diff < 0) {
                    $this->applyInventoryChange($item, abs($diff), true, 'Updated (Decreased)');
                }
            }
        }
    }

    /**
     * Handle the ProductoXFactura "deleting" event.
     */
    public function deleting(ProductoXFactura $item): void
    {
        // Al eliminar el ítem de factura, si es petaco eliminamos sus petaco_productos para que su observer devuelva el inventario
        $producto = $item->producto_id ? Producto::find($item->producto_id) : null;
        if ($producto && $producto->esPetaco()) {
            $items = \App\Models\PetacoProducto::where('productosxfactura_id', $item->id)->get();
            foreach ($items as $petacoItem) {
                $petacoItem->delete();
            }
        }
    }

    /**
     * Handle the ProductoXFactura "deleted" event.
     */
    public function deleted(ProductoXFactura $item): void
    {
        $producto = $item->producto_id ? Producto::find($item->producto_id) : null;
        if (!$producto || !$producto->esPetaco()) {
            $this->applyInventoryChange($item, (float)$item->cantidad, true, 'Deleted');
        }
    }

    /**
     * Aplica el descuento o reposición de stock en inventario para productos individuales.
     */
    protected function applyInventoryChange(ProductoXFactura $item, float $cantidadFactor, bool $isRestore, string $actionContext): void
    {
        if ($cantidadFactor <= 0 || !$item->producto_id) {
            return;
        }

        $this->updateSingleProductStock($item->producto_id, $cantidadFactor, $isRestore, $actionContext);
    }

    /**
     * Actualiza el stock de los insumos asociados a un producto_id específico.
     */
    public function updateSingleProductStock(int $productoId, float $cantidad, bool $isRestore, string $context): void
    {
        $inventarios = Inventario::where('producto_id', $productoId)->get();
        foreach ($inventarios as $inv) {
            if ($inv->descuento_inventario > 0) {
                $unitsToAdjust = $cantidad * (float)$inv->descuento_inventario;
                if ($isRestore) {
                    $inv->stock_inicial = (float)$inv->stock_inicial + $unitsToAdjust;
                    Log::info("Inventario devuelto [{$context}]: {$inv->nombre} +{$unitsToAdjust}");
                } else {
                    $inv->stock_inicial = max(0, (float)$inv->stock_inicial - $unitsToAdjust);
                    Log::info("Inventario descontado [{$context}]: {$inv->nombre} -{$unitsToAdjust}");
                }
                $inv->save();
            }
        }
    }
}
