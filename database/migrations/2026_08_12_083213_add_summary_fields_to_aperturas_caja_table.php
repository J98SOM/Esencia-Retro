<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('aperturas_caja', function (Blueprint $table) {
            $table->decimal('ventas_efectivo', 14, 2)->default(0)->after('monto_final');
            $table->decimal('ventas_tarjeta', 14, 2)->default(0)->after('ventas_efectivo');
            $table->decimal('ventas_qr', 14, 2)->default(0)->after('ventas_tarjeta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aperturas_caja', function (Blueprint $table) {
            $table->dropColumn(['ventas_efectivo', 'ventas_tarjeta', 'ventas_qr']);
        });
    }
};
