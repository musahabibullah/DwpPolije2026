<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Middleware\Authenticate;
use App\Models\VerifikasiPembayaran;
use App\Observers\VerifikasiPembayaranObserver;
use App\Console\Commands\SetupDwp;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function boot(): void
{
    // Redirect pengguna tidak terautentikasi ke homepage
    Authenticate::redirectUsing(fn($request) => route('login'));

    VerifikasiPembayaran::observe(VerifikasiPembayaranObserver::class);

    if ($this->app->runningInConsole()) {
        $this->commands([
            SetupDwp::class,
        ]);
    }

    // **Tambahkan ini untuk memuat routes/api.php**
    $this->loadRoutes();
}

protected function loadRoutes()
{
    Route::prefix('api')
        ->middleware('api')
        ->group(base_path('routes/api.php'));
}
}