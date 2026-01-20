<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
        // Forzar HTTPS en producción
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        //  vista personalizada de paginación
        Paginator::defaultView('vendor.pagination.bootstrap-5');

        // Rate limiter para login
        RateLimiter::for('login', function (Request $request) {
            $email = strtolower($request->input('email', ''));
            $key = $email . '|' . $request->ip();

            return Limit::perMinute(5)->by($key)->response(function () {
                return back()->withErrors([
                    'email' => 'Demasiados intentos de inicio de sesión. Por favor, intenta de nuevo en 1 minuto.',
                ])->withInput();
            });
        });

        // Rate limiter para registro
        RateLimiter::for('register', function (Request $request) {
            return Limit::perHour(3)->by($request->ip())->response(function () {
                return back()->withErrors([
                    'email' => 'Demasiados intentos de registro. Por favor, intenta más tarde.',
                ])->withInput();
            });
        });
    }
}
