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
        Schema::create('estatusxfactura', function (Blueprint $table) {
            $table->id();
            $table->foreignId('productosxfactura_id')->constrained('productosxfactura')->cascadeOnDelete();
            $table->string('estatus')->default('pendiente'); // pendiente, en preparacion, entregado
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estatusxfactura');
    }
};
