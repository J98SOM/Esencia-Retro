<?php

namespace App\Observers;

use App\Models\PetacoProducto;
use App\Models\Inventario;
use Illuminate\Support\Facades\Log;

class PetacoProductoObserver
{
    /**
     * Handle the PetacoProducto "created" event.
     */
    public function created(PetacoProducto $petacoProducto): void
    {
        $this->adjustStock($petacoProducto, false, 'Created');
    }

    /**
     * Handle the PetacoProducto "deleted" event.
     */
    public function deleted(PetacoProducto $petacoProducto): void
    {
        $this->adjustStock($petacoProducto, true, 'Deleted');
    }

    /**
     * Ajusta el stock de los insumos del producto asociado al Petaco.
     */
    protected function adjustStock(PetacoProducto $petacoProducto, bool $isRestore, string $context): void
    {
        $pxf = $petacoProducto->productoXFactura;
        $petacoQty = $pxf ? (float)$pxf->cantidad : 1.0;
        $totalUnits = (float)$petacoProducto->cantidad * $petacoQty;

        if ($totalUnits <= 0 || !$petacoProducto->producto_id) {
            return;
        }

        $inventarios = Inventario::where('producto_id', $petacoProducto->producto_id)->get();
        foreach ($inventarios as $inv) {
            if ($inv->descuento_inventario > 0) {
                $unitsToAdjust = $totalUnits * (float)$inv->descuento_inventario;
                if ($isRestore) {
                    $inv->stock_inicial = (float)$inv->stock_inicial + $unitsToAdjust;
                    Log::info("Inventario devuelto [Petaco {$context}]: {$inv->nombre} +{$unitsToAdjust}");
                } else {
                    $inv->stock_inicial = max(0, (float)$inv->stock_inicial - $unitsToAdjust);
                    Log::info("Inventario descontado [Petaco {$context}]: {$inv->nombre} -{$unitsToAdjust}");
                }
                $inv->save();
            }
        }
    }
}
