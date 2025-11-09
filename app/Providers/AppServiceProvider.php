<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use App\Models\Categoria;

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
        // 🔹 Usa Bootstrap para la paginación
        Paginator::useBootstrap();

        // 🔹 Hace disponibles las categorías en todas las vistas
        View::composer('*', function ($view) {
            $view->with('categorias', Categoria::all());
        });
    }
}
