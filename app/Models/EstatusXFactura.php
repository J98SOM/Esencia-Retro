<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstatusXFactura extends Model
{
    use HasFactory;

    protected $table = 'estatusxfactura';

    protected $fillable = [
        'productosxfactura_id',
        'estatus',
    ];

    public function productoXFactura()
    {
        return $this->belongsTo(ProductoXFactura::class, 'productosxfactura_id');
    }
}
