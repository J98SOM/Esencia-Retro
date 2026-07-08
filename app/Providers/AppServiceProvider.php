<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use App\Models\ProductoXFactura;
use App\Observers\ProductoXFacturaObserver;

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

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
