<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use App\Models\ProductoXFactura;
use App\Models\PetacoProducto;
use App\Observers\ProductoXFacturaObserver;
use App\Observers\PetacoProductoObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ProductoXFactura::observe(ProductoXFacturaObserver::class);
        PetacoProducto::observe(PetacoProductoObserver::class);

        // Auto-run schema modification to add es_admin to mesas table without migration files
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('mesas') && !\Illuminate\Support\Facades\Schema::hasColumn('mesas', 'es_admin')) {
                \Illuminate\Support\Facades\Schema::table('mesas', function ($table) {
                    $table->boolean('es_admin')->default(false);
                });
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning("Error adding es_admin column to mesas: " . $e->getMessage());
        }

        // Obligar a usar HTTPS en producción
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
