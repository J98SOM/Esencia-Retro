<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetacoProducto extends Model
{
    use HasFactory;

    protected $table = 'petaco_productos';

    protected $fillable = [
        'productosxfactura_id',
        'producto_id',
        'cantidad',
    ];

    protected $casts = [
        'cantidad' => 'integer',
    ];

    /**
     * El registro del item en la orden/factura.
     */
    public function productoXFactura()
    {
        return $this->belongsTo(ProductoXFactura::class, 'productosxfactura_id');
    }

    /**
     * El producto / bebida que conforma el Petaco en esta orden.
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
