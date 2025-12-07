<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\BoutiquierMiddleware;
use App\Http\Middleware\CheckBoutiquierStatus;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Alias middleware
        Route::aliasMiddleware('admin', AdminMiddleware::class);
        Route::aliasMiddleware('boutiquier', BoutiquierMiddleware::class);
        Route::aliasMiddleware('checkStatus', CheckBoutiquierStatus::class);

        // Ici tu peux charger les fichiers de routes
        $this->routes(function () {
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));
        });
    }
}
